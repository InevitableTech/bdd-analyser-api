<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('enabled');
            $table->string('repo_url')->nullable();
            $table->string('main_branch')->nullable();
            $table->json('published_tags')->nullable();
            $table->timestamps();

            $table->foreignId('organisation_id')->nullable()->constrained()->onDelete('cascade');

            $table->unique(['name', 'organisation_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
