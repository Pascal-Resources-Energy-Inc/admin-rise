<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMfiToCentersTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('centers') && !Schema::hasColumn('centers', 'mfi')) {
            Schema::table('centers', function (Blueprint $table) {
                $table->string('mfi')->nullable()->after('name');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('centers') && Schema::hasColumn('centers', 'mfi')) {
            Schema::table('centers', function (Blueprint $table) {
                $table->dropColumn('mfi');
            });
        }
    }
}
