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
    Schema::create('job_applications', function (Blueprint $table) {
        $table->id();
        // Link to the User (Alumni)
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        // Link to the Job
        $table->foreignId('job_post_id')->constrained('job_posts')->onDelete('cascade');
        
        // Status: 'pending', 'shortlisted', 'hired', 'rejected'
        $table->string('status')->default('pending');
        
        // Optional: Cover Letter or Resume Link
        $table->text('cover_letter')->nullable();
        
        $table->timestamps();
        
        // Prevent applying twice for the same job
        $table->unique(['user_id', 'job_post_id']); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
