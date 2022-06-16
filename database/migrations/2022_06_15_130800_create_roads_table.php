<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('short_description', '10');
            $table->json('routes');
            $table->integer('user_id');
            $table->timestamps();
        });
        Schema::create('roads_members', function (Blueprint $table) {
            $table->id();
            $table->integer('road_id');
            $table->integer('user_id');
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
        Schema::dropIfExists('roads');
    }
};
