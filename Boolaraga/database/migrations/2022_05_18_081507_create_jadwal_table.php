<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->string('durasi');
            $table->string('start');
            $table->string('end');
            $table->integer('harga');
            $table->time('jam');
            $table->unsignedBigInteger('lapangan_id');
            $table->timestamps();

            $table->foreign('lapangan_id')->references('id')->on('lapangan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jadwal');
    }
}
