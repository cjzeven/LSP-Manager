<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavingItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saving_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('saving_id');
            $table->foreign('saving_id')->references('id')->on('savings')->onDelete('cascade')->onDelete('cascade');
            $table->double('amount');
            $table->dateTime('datetime');
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
        Schema::dropIfExists('saving_items');
    }
}
