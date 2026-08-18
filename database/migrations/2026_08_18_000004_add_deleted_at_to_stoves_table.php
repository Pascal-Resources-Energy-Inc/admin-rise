<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeletedAtToStovesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('stoves') && !Schema::hasColumn('stoves', 'deleted_at')) {
            Schema::table('stoves', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('stoves') && Schema::hasColumn('stoves', 'deleted_at')) {
            Schema::table('stoves', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
}
