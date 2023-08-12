<?php

namespace Tests\Feature;

use Illuminate\Database\Query\Builder;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QueryBuilderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete('delete from categories');
    }

    public function testInsert()
    {
        DB::table('categories')->insert([
            'id' => 'mobil',
            'name' => 'toyota'
        ]);
        DB::table('categories')->insert([
            'id' => 'makanan',
            'name' => 'nasi'
        ]);

        $hasil = DB::select('select count(id) as total from categories');
        self::assertEquals(2, $hasil[0]->total);
    }

    public function testSelect()
    {
        $this->testInsert();
        $collection = DB::table("categories")->select()->get();
        self::assertNotNull($collection);

        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }

    public function testWhere()
    {
        $this->testInsert();

        $collection = DB::table('categories')->where(function (Builder $builder){
            $builder->where('id', '=', 'makanan');
            $builder->orWhere('id', '=', 'mobil');
        })->get();

        $coba = DB::table('categories')->where('name', '=', 'nasi')->get();

        self::assertCount(2, $collection);
        self::assertCount(1, $coba);

        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }

    public function testWhereNull()
    {
        $this->testInsert();

        $collection = DB::table('categories')->whereNull(['description', 'created_at'])->get();
        self::assertCount(2, $collection);
    }

    public function testUpdate()
    {
        $this->testInsert();

        DB::table('categories')->where('id', '=', 'makanan')->update([
            'name' => 'nasi goreng'
        ]);
        $collection = DB::table('categories')->where('name', '=', 'nasi goreng')->get();
        self::assertCount(1, $collection);
    }

    public function testUpdateOrInsert()
    {
        DB::table('categories')->updateOrInsert([
            'id' => 'laptop'
        ], 
        [
            'name' => 'asus'
        ]);

        $collection = DB::table('categories')->where('id', '=', 'laptop')->get();

        self::assertCount(1, $collection);

        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }

    public function testDelete()
    {
        $this->testInsert();

        $collection = DB::table('categories')->where('id', '=', 'laptop')->delete();

        $collection = DB::table('categories')->where('id', '=', 'laptop')->get();
        self::assertCount(0, $collection);
    }

    public function insertProducts()
    {
        $this->testInsert();

        DB::table('products')->insert([
            'id' => '1',
            'name' => 'nasi goreng',
            'category_id' => 'makanan',
            'price' => 150000
        ]);
        DB::table('products')->insert([
            'id' => '2',
            'name' => 'honda',
            'category_id' => 'mobil',
            'price' => 150000000
        ]);
    }

    public function testJoin()
    {
        $this->insertProducts();

        $collection = DB::table('products')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->select('products.id', 'products.name', 'products.price', 'categories.id as category_name')
        ->get();

        self::assertCount(2, $collection);

        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }

    public function insertCategories()
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

    public function testPaginate()
    {
        $this->insertCategories();

        $paginate = DB::table('categories')->paginate(perPage: 2, page: 1);

        // self::assertEquals(1, $paginate->currentPage());
        // self::assertEquals(2, $paginate->lastPage());
        // self::assertEquals(2, $paginate->perPage());
        self::assertEquals(8, $paginate->total());

        $collection = $paginate->items();

        foreach($collection as $item)
        {
            Log::info(json_encode($item));
        }
    }
}
