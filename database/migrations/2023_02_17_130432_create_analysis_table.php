<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalysisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analysis', function (Blueprint $table) {
            $table->id();
            $table->timestamp('run_at');
            $table->string('rules_version');
            $table->json('violations');
            $table->json('violations_meta')->nullable();
            $table->json('summary');
            $table->json('tags');
            $table->json('active_steps');
            $table->json('active_rules');
            $table->json('severities');
            $table->string('branch')->nullable();
            $table->string('commit_hash')->nullable();
            $table->timestamps();

            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analysis');
    }
}
