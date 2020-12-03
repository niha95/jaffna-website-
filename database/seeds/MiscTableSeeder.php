<?php

use App\Models\Misc;
use Illuminate\Database\Seeder;

class MiscTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Misc::create([
            'phone_numbers' => "[]",
            'emails' => "[]",
            'closing_status' => 'closed',
            'youtube' => 'https://www.youtube.com/',
            'facebook' => 'https://www.facebook.com/',
            'twitter' => 'https://www.twitter.com/',
            'instagram' => 'https://www.instgram.com/',
            'google_map' => '{"lat": 30.015152, "lng": 31.213248}',
        ]);
    }
}
