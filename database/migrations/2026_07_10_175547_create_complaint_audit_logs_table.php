<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaint_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->constrained()->onDelete('cascade');
            $table->foreignId('action_by')->constrained('users'); // কে পরিবর্তনটি করলো (অ্যাডমিন বা রিপ্রেজেন্টেটিভ)
            
            $table->string('old_status', 50)->nullable();
            $table->string('new_status', 50);
            $table->json('changes_json')->nullable(); // ঠিক কোন কোন ডেটা চেঞ্জ হয়েছে তার স্ন্যাপশট
            $table->text('remarks')->nullable(); // রিজেক্ট বা হাইড করার কারণ
            
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable(); // কোন ডিভাইস বা ব্রাউজার থেকে করা হয়েছে
            $table->timestamps(); // কখন করা হয়েছে তা স্বয়ংক্রিয়ভাবে ট্র্যাক হবে
            
            $table->index('complaint_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaint_audit_logs');
    }
};