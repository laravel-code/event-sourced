<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Events extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commands', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->json('payload');
            $table->string('status');
            $table->uuid('author_id')->nullable();
            $table->timestamps();
        });

        Schema::create('command_errors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('command_id')->index();
            $table->string('class');
            $table->longText('message');
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('entity_id');
            $table->string('type');
            $table->json('payload');
            $table->integer('version')->default(0);
            $table->uuid('command_id')->nullable();
            $table->uuid('author_id')->nullable();
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
        Schema::dropIfExists('events');
        Schema::dropIfExists('commands_errors');
        Schema::dropIfExists('commands');
    }
}
