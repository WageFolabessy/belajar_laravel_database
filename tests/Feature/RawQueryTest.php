<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RawQueryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete('delete from categories');
    }

    public function testCRUD()
    {
        DB::insert('insert into categories(id, name, description, created_at) values (?, ?, ?, ?)', [
            'mobil', 'toyota', 'mobil baru', '2022-11-08 12:00:00'
        ]);

        $hasil = DB::select('select * from categories where id = ?', ['mobil']);

        self::assertCount(1, $hasil);
        self::assertEquals('mobil', $hasil[0]->id);
        self::assertEquals('toyota', $hasil[0]->name);
        self::assertEquals('mobil baru', $hasil[0]->description);
        self::assertEquals('2022-11-08 12:00:00', $hasil[0]->created_at);
    }

    public function testCRUDNamedParameter()
    {
        DB::insert('insert into categories(id, name, description, created_at) values (:id, :name, :description, :created_at)', [
            'id' => 'mobil',
            'name' => 'toyota',
            'description' => 'mobil baru',
            'created_at' => '2022-11-08 12:00:00'
        ]);

        $hasil = DB::select('select * from categories where id = ?', ['mobil']);

        self::assertCount(1, $hasil);
        self::assertEquals('mobil', $hasil[0]->id);
        self::assertEquals('toyota', $hasil[0]->name);
        self::assertEquals('mobil baru', $hasil[0]->description);
        self::assertEquals('2022-11-08 12:00:00', $hasil[0]->created_at);
    }
}
