<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUssdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ussds', function(Blueprint $table) {
            $table->increments('id');
            $table->string('sessionId');
            $table->string('serviceCode');
            $table->integer('pin_verified');
            $table->integer('is_pin_change');
            $table->integer('level');
            $table->integer('action');
            $table->integer('no_net_salary');
            $table->integer('is_new');
            $table->integer('is_terms');
            $table->integer('is_statement');
            $table->string('client_name');
            $table->float('net_salary');
            $table->float('advance_amount');
            $table->string('company');
            $table->string('manager');
            $table->string('manager_mobile');
            $table->integer('employee_count');
            $table->string('phoneNumber');
            $table->string('text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ussds');
    }
}
