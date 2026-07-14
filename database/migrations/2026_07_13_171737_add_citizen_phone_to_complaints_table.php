<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $blueprint) {
            // citizen_phone কলামটি অ্যাড করছি এবং এটি nullable রাখছি যাতে অ্যাপের ইউজারদের সমস্যা না হয়
            $blueprint->string('citizen_phone')->nullable()->after('citizen_id');
        });
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $blueprint) {
            $blueprint->dropColumn('citizen_phone');
        });
    }
};