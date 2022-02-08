<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('invoice_number');
            $table->foreignId('expedition_id')->nullable()->constrained('expeditions')->onDelete('cascade');
            $table->decimal('expedition_price')->nullable()->default(0);
            $table->string('expedition_receipt')->nullable();
            $table->enum('status',  [
                'unpaid', 'paid', 'packed', 'sent', 'finish',
                'cancel'
            ])->default('unpaid');
            $table->text('attachment')->nullable();
            $table->timestamp('confirm_at')->nullable();
            $table->string('confirm_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
