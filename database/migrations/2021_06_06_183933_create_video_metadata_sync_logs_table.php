<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoMetadataSyncLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_metadata_sync_logs', function (Blueprint $table) {
            $table->bigIncrements('pk');

            $table->unsignedBigInteger('channel_pk');
            $table->string('page_token', 25);
            $table->string('prev_page_token', 25)->nullable(true);
            $table->string('next_page_token', 25)->nullable(true);
            $table->string('total_results')->nullable(true);
            $table->string('results_per_page')->nullable(true);

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
        Schema::dropIfExists('video_metadata_sync_logs');
    }
}
