<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRazmersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('razmers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('marka_id');
            $table->string('title');
            $table->string('price')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->text('specifications')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('razmers');
    }
}
