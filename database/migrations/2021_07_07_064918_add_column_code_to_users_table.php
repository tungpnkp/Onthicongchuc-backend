<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCodeToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('code')->unique()->after("name")->comment("Số báo danh");
            $table->string('otp')->nullable()->unique()->after("code")->comment("Mã xác nhận OTP");
            $table->string('phone')->nullable()->unique()->after("otp");
            $table->string('image')->nullable()->after("phone");
            $table->string('address')->nullable()->after("image");
            $table->tinyInteger('gender')->default(1)->after("address");
            $table->tinyInteger('status')->default(1)->after("gender");
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
            $table->dropColumn('code');
            $table->dropColumn('otp');
            $table->dropColumn('phone');
            $table->dropColumn('image');
            $table->dropColumn('address');
            $table->dropColumn('gender');
            $table->dropColumn('status');
        });
    }
}
