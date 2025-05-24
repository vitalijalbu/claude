<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SitesSeeder extends Seeder
{
    public function run(): void
    {
        // Dati dei siti
        $sites = [
            [
                'id' => 1,
                'slug' => 'italiano',
                'domain' => 'onlyescort.vip',
                'name' => 'Italiano',
                'url' => 'https://it.onlyescort.vip',
                'locale' => 'it',
                'lang' => 'it',
            ],
            [
                'id' => 2,
                'slug' => 'english',
                'domain' => 'onlyescort.vip',
                'name' => 'English',
                'url' => 'https://en.onlyescort.vip',
                'locale' => 'en',
                'lang' => 'en',
            ],
        ];

        // Upsert per i siti
        foreach ($sites as $site) {
            DB::table('sites')->upsert(
                [
                    'slug' => $site['slug'],
                    'domain' => $site['domain'],
                    'name' => $site['name'],
                    'url' => $site['url'],
                    'locale' => $site['locale'],
                    'lang' => $site['lang'],
                    'attributes' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                ['slug'],
                ['domain', 'name', 'url', 'locale', 'lang', 'attributes', 'updated_at']
            );
        }
    }
}
