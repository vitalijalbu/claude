<?php

declare(strict_types=1);

namespace App\Helpers;

use Aws\SecretsManager\SecretsManagerClient;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SecretsHelper
{
    protected $client;

    protected $configVariables = [];

    protected $cache;

    protected $cacheExpiry;

    protected $cacheStore;

    protected $debug;

    protected $secret_name;

    protected $configOverride;

    protected bool $keyRotation = false;

    public function __construct()
    {
        $this->cache = config('secrets.cache-enabled', true);

        $this->cacheExpiry = config('secrets.cache-expiry', 0);

        $this->cacheStore = config('secrets.cache-store', 'file');

        $this->secret_name = config('secrets.secret-name', '');

        $this->debug = config('secrets.debug', false);

        $this->keyRotation = false; // config('secrets.key-rotation');

        $this->configOverride = config('secrets.config-override', []);
    }

    public function loadSecrets()
    {
        if (empty($this->secret_name)) {
            if ($this->debug) {
                info('[Secrets] Skip startup because secret_name is empty');
            }

            return;
        }

        // Load vars from datastore to env
        if ($this->debug) {
            info('[Secrets] Startup');
            $start = microtime(true);
        }

        if (! $this->checkCache()) {
            if ($this->debug) {
                info('[Secrets] Load secrets from AWS');
            }

            // Cache has expired need to refresh the cache from Datastore
            $this->getVariables();
        }

        // Process variables in config that need updating
        $this->updateConfigs();

        if ($this->debug) {
            $time_elapsed_secs = microtime(true) - $start;
            info('[Secrets] Elapsed time: ' . number_format($time_elapsed_secs, 3) . 's');
        }
    }

    protected function parsePrefixes(string $prefixes)
    {
        $list = explode(',', $prefixes);

        $out = [];
        foreach ($list as $item) {
            $el = explode(':', $item);
            $key = count($el) > 1 ? $el[1] : $el[0];
            $out[$key] = $el[0];
        }

        return $out;
    }

    protected function checkCache()
    {
        if ($this->keyRotation) {
            $cachedNextRotationDate = Cache::store($this->cacheStore)->get('AWSSecretsNextRotationDate');
            if (
                blank($cachedNextRotationDate) ||
                $cachedNextRotationDate < Carbon::now()
            ) {
                return false;
            }
        }

        $vars = Cache::store($this->cacheStore)->get('SecretsVarsList', []);
        if (empty($vars)) {
            return false;
        }

        $ret = false;
        foreach ($vars as $variable => $configPath) {
            $val = Cache::store($this->cacheStore)->get($configPath);
            if (! is_null($val)) {
                putenv(sprintf('%s=%s', $variable, $val));
                $ret = true;
            }
        }

        return $ret;
    }

    protected function getVariables()
    {
        try {
            $this->client = new SecretsManagerClient([
                'version' => '2017-10-17',
                'region' => config('secrets.region'),
            ]);

            $result = $this->client->getSecretValue(['SecretId' => $this->secret_name]);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return;
        }

        if ($this->keyRotation) {
            $nextRotationDateToCache = null;
        }

        $secretValues = json_decode($result['SecretString'], true);

        if ($this->keyRotation) {
            $nextRotationDate = Carbon::instance($result['NextRotationDate']);
            if ($nextRotationDate < $nextRotationDateToCache) {
                $nextRotationDateToCache = $nextRotationDate;
            }
        }

        $secretsVarsList = [];

        if (isset($secretValues['name'], $secretValues['value'])) {
            $key = $result['Name'] . '.' . $secretValues['name'];
            $secretsVarsList[] = $key;
            $secret = $secretValues['value'];
            putenv(sprintf('%s=%s', $key, $secret));
            $this->storeToCache($key, $secret);
        } else {
            foreach ($secretValues as $key => $value) {
                $secretsVarsList[] = $key;
                putenv(sprintf('%s=%s', $key, $value));
                $this->storeToCache($key, $value);
            }
        }

        $this->configVariables = $secretsVarsList;
        $this->storeToCache('SecretsVarsList', $this->configVariables);

        if ($this->keyRotation) {
            $this->storeToCache('AWSSecretsNextRotationDate', $nextRotationDateToCache);
        }
    }

    protected function updateConfigs()
    {
        $resetDB = false;

        $this->configVariables = Cache::store($this->cacheStore)->get('SecretsVarsList', []);

        foreach ($this->configVariables as $variable => $configPath) {
            config([$configPath => env($variable)]); // @phpstan-ignore larastan.noEnvCallsOutsideOfConfig

            if (in_array($configPath, $this->configOverride)) {
                foreach ($this->configOverride as $overrideKey => $overrideValue) {
                    if ($overrideValue !== $configPath) {
                        continue;
                    }

                    $resetDB |= Str::contains($overrideKey, 'database.connections.mysql.');
                    if ($this->debug) {
                        info('[Secrets] Overriding "' . $overrideKey . '": "' . Cache::store($this->cacheStore)->get($overrideValue) . '"');
                    }

                    Config::set($overrideKey, Cache::store($this->cacheStore)->get($overrideValue, env($overrideValue))); // @phpstan-ignore larastan.noEnvCallsOutsideOfConfig
                }
            }
        }

        if ($resetDB) {
            DB::purge('mysql');
            DB::reconnect('mysql');
        }
    }

    protected function storeToCache($name, $val)
    {
        if ($this->cache) {
            if ($this->debug) {
                info('[Secrets] Save secret "' . $name . '": "' . json_encode($val) . '"');
            }

            Cache::store($this->cacheStore)->put($name, $val, $this->cacheExpiry * 60);
        }
    }
}
