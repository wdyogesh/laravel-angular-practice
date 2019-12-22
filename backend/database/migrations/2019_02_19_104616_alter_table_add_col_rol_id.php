<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAddColRolId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table)
        {
            $table->smallInteger('role_id')->after('id');
            $table->dropColumn('name');
            $table->string('first_name', 50)->after('role_id');
            $table->string('last_name', 50)->after('first_name');
            $table->enum('is_active', [0, 1])->default(0)->after('last_name');
            $table->string('image',255)->nullable()->after('is_active');
            $table->string('ip_address',50)->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table)
        {
            $table->string('name');
            $table->dropColumn('role_id');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('is_active');
            $table->dropColumn('image');
            $table->dropColumn('ip_address');
        });
    }
}
