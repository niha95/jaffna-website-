<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->increments('id');

            createLocalizedColumns($table, [
                'title' => 'string',
                'content' => 'string',
                'footer' => 'string',
                'status' => ['char', 20]
            ]);

            $table->integer('order');

            $table->string('link');

            $table->integer('image_id');

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
        Schema::drop('slides');
    }
}
