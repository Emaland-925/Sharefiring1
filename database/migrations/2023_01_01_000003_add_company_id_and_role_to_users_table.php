<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyIdAndRoleToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->after('email');
            $table->string('role')->default('employee')->after('company_id'); // admin, company, employee
            $table->integer('points')->default(0)->after('role');
            $table->integer('level')->default(1)->after('points');
            $table->string('profile_image')->nullable()->after('level');
            $table->string('language_preference')->default('en')->after('profile_image');
            
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn(['company_id', 'role', 'points', 'level', 'profile_image', 'language_preference']);
        });
    }
}