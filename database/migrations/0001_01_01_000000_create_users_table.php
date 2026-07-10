<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('phone', 15)->unique(); // ওটিপি লগইনের জন্য মূল ফিল্ড
        $table->string('email')->nullable()->unique();
        $table->string('password')->nullable(); // ফেসবুক/গুগল লগইনের ক্ষেত্রে পাসওয়ার্ড নাল হতে পারে
        $table->enum('role', ['citizen', 'representative', 'admin', 'super_admin'])->default('citizen');
        
        // জনপ্রতিনিধি বা অ্যাডমিন কোন এলাকার দায়িত্বে আছেন তার ট্র্যাকিং
        $table->foreignId('division_id')->nullable()->constrained();
        $table->foreignId('district_id')->nullable()->constrained();
        $table->foreignId('upazila_id')->nullable()->constrained();
        $table->foreignId('union_id')->nullable()->constrained();

        $table->string('nid_number', 20)->nullable()->unique();
        $table->boolean('is_nid_verified')->default(false);
        $table->enum('status', ['active', 'suspended'])->default('active');
        $table->text('fcm_token')->nullable(); // পুশ নোটিফিকেশনের জন্য
        
        $table->rememberToken();
        $table->timestamps();

        // ইনডেক্সিং (পারফরম্যান্স ফাস্ট করার জন্য)
        $table->index('phone');
        $table->index('role');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
