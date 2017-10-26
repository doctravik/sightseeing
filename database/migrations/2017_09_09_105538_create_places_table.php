<?php

use Illuminate\Support\Facades\Schema;
use Phaza\LaravelPostgis\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->increments('id');
            $table->float('longitude', 11, 8);
            $table->float('latitude', 10, 8);
            $table->string('name', 80)->unique();
            $table->string('address', 255);
            $table->string('country', 255);
            $table->text('description')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->point('location')->unique();
            $table->string('image')->nullable();
            $table->integer('visits_count')->default(0);
            $table->string('slug')->unique();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places');
    }
}
