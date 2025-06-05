<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\InitRoles;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InitDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        return DB::transaction(function () {
            $this->info('Initializing database roles...');
            InitRoles::dispatch();
            $this->info('Database roles initialized successfully.');

            $this->info('Initializing database permissions...');

            return static::SUCCESS;
        });
    }
}
