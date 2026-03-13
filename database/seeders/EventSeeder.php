<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('events')->insert([
            [
                'title' => 'Sample Event 1',
                'description' => 'This is a description for Sample Event 1.',
                'event_date' => Carbon::now()->addDays(10),
                'organizer_name' => 'Organizer 1',
                'location_name' => 'Location 1',
                'image_path' => 'images/sample1.jpg',
                'link_url' => 'https://example.com/event1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Sample Event 2',
                'description' => 'This is a description for Sample Event 2.',
                'event_date' => Carbon::now()->addDays(20),
                'organizer_name' => 'Organizer 2',
                'location_name' => 'Location 2',
                'image_path' => 'images/sample2.jpg',
                'link_url' => 'https://example.com/event2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}