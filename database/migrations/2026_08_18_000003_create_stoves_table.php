<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStovesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('stoves')) {
            Schema::create('stoves', function (Blueprint $table) {
                $table->increments('id');
                $table->string('serial_number')->unique();
                $table->unsignedInteger('client_id')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('stoves');
    }
}
