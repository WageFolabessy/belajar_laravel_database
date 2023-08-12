<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // bisa mengubah nama table yang awalnya table_product menjadi products
        Schema::create('products', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->string('name', 100)->nullable(false);
            $table->integer('price')->nullable(false);
            $table->text('description')->nullable(true);
            $table->string('category_id', 100)->nullable(false);
            $table->timestamp('created_at')->useCurrent();
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
