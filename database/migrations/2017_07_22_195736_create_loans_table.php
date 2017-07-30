<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id');
            $table->decimal('amount_requested');
            $table->decimal('amount_processed');
            $table->double('daily_interest');
            $table->double('fees');
            $table->double('total');
            $table->text('reason');
            $table->string('transaction_ref')->default('');
            $table->double('transaction_fee');
            $table->double('paid');
            $table->string('invoiced');
            $table->integer('status');
            $table->string('type');
            $table->text('purpose');
            $table->text('payment_status');
            $table->text('payment_response');
            $table->string('provider');
            $table->double('net_salary');
            $table->string('date_disbursed');
            $table->integer('deleted');
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
        Schema::drop('loans');
    }
}
