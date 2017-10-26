<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_admin')->after('email')->default(0)->index();
            });
        }

        if (Schema::hasColumn('users', 'name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('name', 'first_name');
            });

            Schema::table('users', function (Blueprint $table) {
                $table->string('last_name')->nullable()->after('first_name');
            });
        }


        if (config('netcore.module-user.socialite')) {
            Schema::create('user_oauth_identities', function (Blueprint $table) {
                $table->increments('id');
                $table->string('provider');
                $table->string('provider_id')->index();
                $table->unsignedInteger('user_id')->index();
                $table->timestamp('last_login_at')->default(DB::raw('NOW()'));
                $table->timestamps();

                $table->unique(['provider', 'user_id'], 'user_provider_unique'); // user cannot have two same providers
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_admin');
            });
        }

        if (Schema::hasColumn('users', 'first_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('first_name', 'name');
                $table->dropColumn('last_name');
            });
        }
        if (config('netcore.module-user.socialite')) {
            Schema::dropIfExists('user_oauth_identities');
        }
    }
}
