<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number', 30)->unique(); // যেমন: BD-2026-DHK-000245
            $table->foreignId('citizen_id')->constrained('users')->onDelete('restrict'); // ইউজার ডিলিট হলেও কমপ্লেইন ডিলিট হবে না
            
            $table->string('title', 150);
            $table->text('description');
            
            // লোকেশন ডাটা (GPS এবং এরিয়া)
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->foreignId('division_id')->constrained();
            $table->foreignId('district_id')->constrained();
            $table->foreignId('upazila_id')->constrained();
            $table->foreignId('union_id')->nullable()->constrained();
            
            // ম্যানেজমেন্ট ও এসকেলেশন
            $table->foreignId('current_representative_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('escalation_level')->default(1); // 1 = Ward, 2 = Union, 3 = Upazila, etc.
            
            // স্ট্যাটাস
            $table->enum('status', ['pending', 'investigating', 'resolved_claimed', 'solved', 'rejected', 'hidden'])->default('pending');
            
            // নাগরিক রেটিং ও ফিডব্যাক
            $table->tinyInteger('citizen_rating')->nullable(); // 1 to 5
            $table->text('citizen_feedback')->nullable();
            
            $table->timestamps();
            $table->softDeletes(); // ডাটাবেজ থেকে কখনো হার্ড ডিলিট হবে না, শুধু ডিলিটেড ফ্ল্যাগ পড়বে

            // ইনডেক্সিং ফর ফাস্টার ফিল্টারিং অ্যান্ড ড্যাশবোর্ড কাউন্টস
            $table->index('tracking_number');
            $table->index('status');
            $table->index(['district_id', 'upazila_id']); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};