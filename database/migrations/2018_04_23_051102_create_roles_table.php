<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Role;
use App\User;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->index('user');
            $table->string('name', 255)->index('name');

            $table->unique(['user_id', 'name']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable();
        });

        foreach (User::all() as $user) {
            $user->addRole($user->group->name);
            $user->setMainRole($user->group->name);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
        Schema::table('users', function (Blueprint $table) {
           $table->dropColumn('role_id');
        });
    }
}
