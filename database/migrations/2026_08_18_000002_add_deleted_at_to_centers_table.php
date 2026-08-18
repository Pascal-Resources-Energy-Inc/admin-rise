<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeletedAtToCentersTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('centers') && !Schema::hasColumn('centers', 'deleted_at')) {
            Schema::table('centers', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('centers') && Schema::hasColumn('centers', 'deleted_at')) {
            Schema::table('centers', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
}
