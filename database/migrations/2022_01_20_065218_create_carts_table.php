<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')
            ->constrained('products')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('price')->default(0);
            $table->timestamps();

            $table->primary(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
