<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ColorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $colors = [
            ['color_id' => 1, 'color_name' => 'Lavender', 'color_hex' => '#7986cb'],
            ['color_id' => 2, 'color_name' => 'Sage', 'color_hex' => '#33b679'],
            ['color_id' => 3, 'color_name' => 'Grape', 'color_hex' => '#8e24aa'],
            ['color_id' => 4, 'color_name' => 'Flamingo', 'color_hex' => '#e67c73'],
            ['color_id' => 5, 'color_name' => 'Banana', 'color_hex' => '#f6c026'],
            ['color_id' => 6, 'color_name' => 'Tangerine', 'color_hex' => '#f5511d'],
            ['color_id' => 7, 'color_name' => 'Peacock', 'color_hex' => '#039be5'],
            ['color_id' => 8, 'color_name' => 'Graphite', 'color_hex' => '#616161'],
            ['color_id' => 9, 'color_name' => 'Blueberry', 'color_hex' => '#3f51b5'],
            ['color_id' => 10, 'color_name' => 'Basil', 'color_hex' => '#0b8043'],
            ['color_id' => 11, 'color_name' => 'Tomato', 'color_hex' => '#d60000'],
            ['color_id' => 12, 'color_name' => 'Who knows', 'color_hex' => '#039be5'],
        ];

        DB::table('colors')->insert($colors);
    }
}
