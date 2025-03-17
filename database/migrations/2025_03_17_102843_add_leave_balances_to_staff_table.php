<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->decimal('annual_leave_balance', 5, 1)->default(30.00)->after('shift_hours');
            $table->decimal('sick_leave_balance', 5, 1)->default(15.00)->after('annual_leave_balance');
        });
    }

    public function down()
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn(['annual_leave_balance', 'sick_leave_balance']);
        });
    }
};

