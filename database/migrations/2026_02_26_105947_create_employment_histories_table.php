<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employment_histories', function (Blueprint $table) {
            $table->id();
            // Links directly to the user
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('company_name');
            $table->string('job_title');
            $table->string('employment_type')->nullable(); // e.g., Full-time, Part-time, Freelance
            
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employment_histories');
    }
};