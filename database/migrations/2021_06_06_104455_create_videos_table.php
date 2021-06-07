<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->bigIncrements('pk');

            $table->string('title');
            $table->string('yt_id', 50)->unique();
            $table->text('description')->nullable(true);
            $table->unsignedBigInteger('channel_pk');
            $table->boolean('downloaded')->default(false);
            $table->string('file_path')->nullable(true);

            $table->foreign('channel_pk')
                ->references('pk')->on('channels')
                ->onDelete('cascade')
                ->onUpdate('cascade');

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
        Schema::dropIfExists('videos');
    }
}
