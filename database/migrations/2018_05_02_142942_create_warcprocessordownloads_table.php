<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarcprocessordownloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warcprocessor_downloads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('downloads_counter')->default(0);
            $table->timestamps();

        });
        DB::table('warcprocessor_downloads')->insert([
            ['downloads_counter' => 0]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warcprocessor_downloads');
    }
}
