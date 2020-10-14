<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorpusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corpus', function (Blueprint $table) {
            $table->uuid('uuid')->unique();
            $table->unsignedInteger('user_id');
            $table->string('name');
            $table->float('size')->default(0);
            $table->string('description')->nullable();
            $table->string('all_content_languages',1000)->default('es');
            $table->string('all_content_types',1000)->nullable();
            $table->string('all_content_dates',1000)->nullable();
            $table->unsignedInteger('total_sites')->default(0);
            $table->float('spam_amount')->default(0);
            $table->unsignedInteger('downloads')->default(0);
            $table->enum('status',['public','private', 'trash','removed'])->default('public');
            $table->string('path')->nullable();
            $table->string('spamDir')->nullable();
            $table->string('hamDir')->nullable();

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('corpus');
    }
}
