<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitorsMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitors_messages', function (Blueprint $table) {
            $table->increments('id');

            $table->string('sender_name');
            $table->string('sender_email_address');
            $table->string('sender_phone_number');
            $table->string('subject');
            $table->string('message');
            $table->char('status', 20);

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
        Schema::drop('visitors_messages');
    }
}
