<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


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
            $table->increments('id');
            $table->integer('user_id');
            $table->string('file_path');
            $table->string('url');
            $table->string('region');
            $table->string('key');
            $table->integer('bucket_count')->default(0);
            $table->integer('answer_count')->default(0);
            $table->time('duration')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamp('created_date');
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

