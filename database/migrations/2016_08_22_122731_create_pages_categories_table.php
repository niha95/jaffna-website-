<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreatePagesCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages_categories', function (Blueprint $table) {
            $table->increments('id');

            $table->string('slug');
            $table->string('path');
            $table->string('layout');

            createLocalizedColumns($table, [
                'name' => 'string',
                'content' => 'text',
            ]);

            NestedSet::columns($table);

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
        Schema::drop('pages_categories');
    }
}
