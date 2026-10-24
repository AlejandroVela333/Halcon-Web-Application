<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvidencePhotosTable extends Migration
{
    public function up()
    {
        Schema::create('evidence_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('uploaded_by');
            $table->enum('type', ['Loaded', 'Delivery']);
            $table->string('file_path', 255);
            $table->dateTime('uploaded_at');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('uploaded_by')->references('id')->on('users');
            $table->index(['order_id', 'type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('evidence_photos');
    }
}
