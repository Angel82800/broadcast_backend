<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoEventLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_event_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('video_id');
            $table->string('device_uuid');
            $table->string('app_key');
            $table->timestamp('created_date');
            $table->boolean('is_viewed');
            $table->time('duration');
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
        Schema::dropIfExists('video_event_log');
    }

}
