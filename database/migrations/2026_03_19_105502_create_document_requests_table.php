<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tracking_code')->unique(); // E.g., REQ-2026-0001
            $table->string('document_type'); // OTR, Diploma, Good Moral, etc.
            $table->integer('copies')->default(1);
            $table->text('purpose')->nullable();
            
            // Statuses: Pending, Processing, Ready for Pickup, Claimed, Rejected
            $table->string('status')->default('Pending'); 
            
            $table->text('remarks')->nullable(); // For registrar to tell the alumni "Please bring 2 ID pictures", etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_requests');
    }
};
