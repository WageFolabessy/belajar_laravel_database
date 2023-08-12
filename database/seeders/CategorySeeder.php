<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            'id' => 'mobil',
            'name' => 'toyota'
        ]);
        DB::table('categories')->insert([
            'id' => 'makanan',
            'name' => 'nasi'
        ]);
        DB::table('categories')->insert([
            'id' => 'musik',
            'name' => 'gitar'
        ]);
        DB::table('categories')->insert([
            'id' => 'band',
            'name' => 'the beatles'
        ]);
        DB::table('categories')->insert([
            'id' => 'motor',
            'name' => 'beat'
        ]);
        DB::table('categories')->insert([
            'id' => 'lagu',
            'name' => 'hey jude'
        ]);
        DB::table('categories')->insert([
            'id' => 'kelamin',
            'name' => 'perempuan'
        ]);
        DB::table('categories')->insert([
            'id' => 'club',
            'name' => 'fcb'
        ]);
    }
}
