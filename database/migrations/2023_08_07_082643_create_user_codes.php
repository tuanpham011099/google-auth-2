<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('verification_code')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('phone_verified')->default(0);
            $table->boolean('enable_2fa')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('verification_code');
            $table->dropColumn('phone');
            $table->dropColumn('phone_verified');
            $table->dropColumn('enable_2fa');
        });
    }
};
