<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function(Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('surname');
            $table->integer('mobile_number')->unsigned();
            $table->string('employee_number');
            $table->text('id_number');
            $table->decimal('net_salary')->default(0);
            $table->text('email');
            $table->tinyInteger('is_checkoff');
            $table->integer('status');
            $table->text('activation_code');
            $table->integer('organization_id')->unsigned()->nullable();
            $table->decimal('withholding_balance')->default(0);
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::drop('customers');
    }
}
