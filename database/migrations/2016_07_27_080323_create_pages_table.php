<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');

            $table->string('slug');

            createLocalizedColumns($table, [
                'title' => 'string',
                'meta_keywords' => 'string',
                'meta_description' => 'string',
                'status' => ['char', 20]
            ]);

            $table->text('content');

            $table->integer('featured_image_id');
            $table->integer('icon_image_id');
            $table->integer('page_category_id');

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
        Schema::drop('pages');
    }
}
