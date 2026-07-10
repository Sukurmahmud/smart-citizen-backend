<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained()->onDelete('cascade');
            $table->string('name', 50);
            $table->string('bn_name', 50);
            $table->timestamps();

            // দ্রুত সার্চ করার জন্য ইনডেক্স
            $table->index('division_id'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};