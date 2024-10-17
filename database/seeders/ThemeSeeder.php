<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Theme;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Theme::create([
            'theme' => 'ケンカが強そうな言葉',
        ]);
        Theme::create([
            'theme' => 'お金持ちそうな名字',
        ]);
        Theme::create([
            'theme' => '出会いたくないお化け',
        ]);
        Theme::create([
            'theme' => '隣に引っ越して来てほしくないタイプの人',
        ]);
    }
}
