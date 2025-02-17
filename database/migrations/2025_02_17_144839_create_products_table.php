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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId("category_id")->constrained("categories");
            $table->foreignId("department_id");
            $table->foreignId("manufacturer_id");
            $table->string("product_number")->unique();
            $table->string("upc")->unique();
            $table->string("sku")->unique();
            $table->decimal("regular_price_sale", 10, 2); //2 decimalna mesta max
            $table->decimal("sale_price", 10, 2); //2 decimalna mesta max
            $table->text("description"); //String ima maksimum of 255 karaktera, dok text nema specificnu duzinu
            $table->timestamps();

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
