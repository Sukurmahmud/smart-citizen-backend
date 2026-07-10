<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaint_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->constrained()->onDelete('cascade');
            $table->string('file_path', 255);
            $table->enum('file_type', ['image', 'video', 'document']);
            $table->enum('uploaded_by', ['citizen', 'representative']); // প্রমাণটি কে দিয়েছে তা নিশ্চিত করা
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaint_attachments');
    }
};