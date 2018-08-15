<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetaTables extends Migration
{
    public function up()
    {
        Schema::create('meta', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('metable');
            $table->string('key', 128);
            $table->text('value');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['metable_id', 'metable_type']);
            $table->index('key');
        });
    }

    public function down()
    {
        Schema::drop('meta');
    }
}
