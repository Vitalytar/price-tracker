<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Class UserRoles
 */
class UserRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->increments('user_role_id');
            $table->string('user_role_code');
            $table->string('user_role_name');
        });

        DB::table('user_roles')->insert([
                [
                    'user_role_id' => null,
                    'user_role_code' => 'basic',
                    'user_role_name' => 'Basic'
                ],
                [
                    'user_role_id' => null,
                    'user_role_code' => 'premium',
                    'user_role_name' => 'Premium'
                ],
                [
                    'user_role_id' => null,
                    'user_role_code' => 'administrator',
                    'user_role_name' => 'Administrator'
                ],
                [
                    'user_role_id' => null,
                    'user_role_code' => 'moderator',
                    'user_role_name' => 'Moderator'
                ]
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_roles');
    }
}
