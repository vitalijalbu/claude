<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\TagGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class TagsSeeder extends Seeder
{
    public function run()
    {
        $tagGroupsJson = File::get(database_path('seeders/data/tag_groups.json'));
        $tagsJson = File::get(database_path('seeders/data/tags.json'));

        $tagGroupsData = json_decode($tagGroupsJson, true);
        $tagsData = json_decode($tagsJson, true);

        // Seed Tag Groups
        foreach ($tagGroupsData as $groupData) {
            TagGroup::updateOrCreate(
                ['slug' => $groupData['slug']],
                [
                    'id' => $groupData['id'],
                    'name' => ['it' => $groupData['name']],
                ]
            );
        }

        // Seed Tags
        foreach ($tagsData as $tagData) {
            Tag::updateOrCreate(
                ['slug' => $tagData['slug']],
                [
                    'id' => $tagData['id'],
                    'group_id' => $tagData['group_id'],
                    'name' => ['it' => $tagData['name']],
                ]
            );
        }
    }
}
