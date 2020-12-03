<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMiscTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('misc', function (Blueprint $table) {
            $table->increments('id');

            createLocalizedColumns($table, [
                'site_name' => 'string',
                'site_word_title' => ['string', 500],
                'site_word_content' => 'text',
                'meta_description' => 'string',
                'meta_keywords' => 'string',
                'address' => ['string', 500],
                'other_contact_data' => 'text',
                'closing_message' => 'text',
            ]);

            $table->char('closing_status', 20);
            $table->string('phone_numbers', 500);
            $table->string('emails', 500);
            $table->string('google_map', 1000);
            $table->string('postal_code');

            $table->string('facebook');
            $table->string('twitter');
            $table->string('youtube');
            $table->string('instagram');
            $table->string('google_plus');
            $table->string('linked_in');

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
        Schema::drop('misc');
    }
}
