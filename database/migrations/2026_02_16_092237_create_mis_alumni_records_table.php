<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mis_alumni_records', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique(); 
            $table->string('first_name');
            $table->string('last_name');
            $table->string('course')->nullable();
            $table->year('year_graduated')->nullable();
            $table->date('birthdate'); 
            $table->boolean('is_claimed')->default(false); 
            
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mis_alumni_records');
    }
};