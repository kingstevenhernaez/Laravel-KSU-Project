<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::create('job_posts', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('slug');
        $table->string('company_name');
        $table->string('location');
        $table->string('job_type'); // Full time, Part time
        $table->string('salary')->nullable();
        $table->date('deadline');
        $table->text('description')->nullable();
        $table->integer('status')->default(1);
        $table->unsignedBigInteger('posted_by')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
