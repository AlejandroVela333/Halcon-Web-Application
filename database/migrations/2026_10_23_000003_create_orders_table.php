<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 30)->unique();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('created_by');
            $table->dateTime('order_date');
            $table->string('delivery_address', 255);
            $table->text('notes')->nullable();
            $table->enum('status', ['Ordered', 'In Process', 'In Route', 'Delivered'])->default('Ordered');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('created_by')->references('id')->on('users');
            $table->index(['status', 'order_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
