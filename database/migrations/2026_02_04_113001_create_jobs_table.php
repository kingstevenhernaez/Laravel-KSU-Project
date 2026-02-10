<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title');        // e.g. "Software Engineer"
            $table->string('company');      // e.g. "Yapakuzi Networks"
            $table->string('location');     // e.g. "Tabuk City"
            $table->string('type');         // e.g. Full-time, Part-time, Remote
            $table->text('description');    // Full HTML description
            $table->string('salary')->nullable();
            $table->string('link')->nullable(); // External application link
            $table->string('contact_email')->nullable();
            $table->date('deadline')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};