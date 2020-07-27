<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLivingItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('living_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('living_id');
            $table->foreign('living_id')->references('id')->on('livings')->onDelete('cascade')->onDelete('cascade');
            $table->string('name', 150);
            $table->double('amount');
            $table->boolean('paid')->default(0);
            $table->boolean('is_required')->default(0);
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
        Schema::dropIfExists('living_items');
    }
}
