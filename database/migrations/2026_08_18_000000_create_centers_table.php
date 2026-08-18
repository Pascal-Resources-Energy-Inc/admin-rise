<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCentersTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('centers')) {
            Schema::create('centers', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->unique();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('centers');
    }
}
