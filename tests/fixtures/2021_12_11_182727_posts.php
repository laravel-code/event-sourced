<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Posts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->foreignUuid('id');
            $table->string('title')->index();
            $table->longText('body');
            $table->longText('status');
            $table->string('secret_key')->nullable();
            $table->bigInteger('version');
            $table->timestamps();
            $table->softDeletes();
        });

        \DB::table('posts')->insert([
            'id' => \Illuminate\Support\Str::uuid(),
            'title' => 'Post 1',
            'body' => 'Description of a blog post NO 1',
            'status' => 'draft',
            'version' => 1,
        ]);

        \DB::table('posts')->insert([
            'id' => \Illuminate\Support\Str::uuid(),
            'title' => 'Post 2',
            'body' => 'Description of a blog post NO 2',
            'status' => 'active',
            'version' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
