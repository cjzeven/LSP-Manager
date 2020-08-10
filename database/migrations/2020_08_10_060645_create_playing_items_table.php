<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayingItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playing_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('playing_id');
            $table->foreign('playing_id')->references('id')->on('playings')->onDelete('cascade')->onUpdate('cascade');
            $table->dateTime('datetime');
            $table->double('amount')->default(0);
            $table->text('receipt_photo')->nullable();

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
        Schema::dropIfExists('playing_items');
    }
}
