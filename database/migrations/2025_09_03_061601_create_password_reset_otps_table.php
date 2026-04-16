<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordResetOtpsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('password_reset_otps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->index();
            $table->string('otp', 6);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('password_reset_otps');
    }
}