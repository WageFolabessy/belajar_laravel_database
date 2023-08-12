<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete('delete from categories');
    }

    public function testOtomatisTransactionSuccess()
    {
        DB::transaction(function(){
            DB::insert('insert into categories(id, name, description, created_at) values (:id, :name, :description, :created_at)', [
                'id' => 'mobil',
                'name' => 'toyota',
                'description' => 'mobil baru',
                'created_at' => '2022-11-08 12:00:00'
            ]);
    
            DB::insert('insert into categories(id, name, description, created_at) values (:id, :name, :description, :created_at)', [
                'id' => 'makanan',
                'name' => 'nasi',
                'description' => 'nasi enak',
                'created_at' => '2022-11-08 12:00:00'
            ]);
        });

        $hasil = DB::select('select * from categories');
        self::assertCount(2, $hasil);
    }

    public function testOtomatisTransactionFail()
    {
        try{
            DB::transaction(function(){
                DB::insert('insert into categories(id, name, description, created_at) values (:id, :name, :description, :created_at)', [
                    'id' => 'mobil',
                    'name' => 'toyota',
                    'description' => 'mobil baru',
                    'created_at' => '2022-11-08 12:00:00'
                ]);
        
                DB::insert('insert into categories(id, name, description, created_at) values (:id, :name, :description, :created_at)', [
                    'id' => 'mobil',
                    'name' => 'nasi',
                    'description' => 'nasi enak',
                    'created_at' => '2022-11-08 12:00:00'
                ]);
            });
        }catch(QueryException $error){
        }

        $hasil = DB::select('select * from categories');
        self::assertCount(0, $hasil);
    }

    public function testManualTransactionSuccess()
    {
        try{
            DB::beginTransaction();
            DB::insert('insert into categories(id, name, description, created_at) values (:id, :name, :description, :created_at)', [
                'id' => 'mobil',
                'name' => 'toyota',
                'description' => 'mobil baru',
                'created_at' => '2022-11-08 12:00:00'
            ]);
    
            DB::insert('insert into categories(id, name, description, created_at) values (:id, :name, :description, :created_at)', [
                'id' => 'makanan',
                'name' => 'nasi',
                'description' => 'nasi enak',
                'created_at' => '2022-11-08 12:00:00'
            ]);
            DB::commit();
        }catch(QueryException $error){
            DB::rollBack();
        }
        

        $hasil = DB::select('select * from categories');
        self::assertCount(2, $hasil);
    }

    public function testManualTransactionFail()
    {
        try{
            DB::beginTransaction();
            DB::insert('insert into categories(id, name, description, created_at) values (:id, :name, :description, :created_at)', [
                'id' => 'mobil',
                'name' => 'toyota',
                'description' => 'mobil baru',
                'created_at' => '2022-11-08 12:00:00'
            ]);
    
            DB::insert('insert into categories(id, name, description, created_at) values (:id, :name, :description, :created_at)', [
                'id' => 'mobil',
                'name' => 'nasi',
                'description' => 'nasi enak',
                'created_at' => '2022-11-08 12:00:00'
            ]);
            DB::commit();
        }catch(QueryException $error){
            DB::rollBack();
        }
        
        $hasil = DB::select('select * from categories');
        self::assertCount(0, $hasil);
    }
}
