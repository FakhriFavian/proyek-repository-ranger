<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->unsignedInteger('stok_total')->default(0)->after('deskripsi');
            $table->unsignedInteger('stok_tersedia')->default(0)->after('stok_total');
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['stok_total', 'stok_tersedia']);
        });
    }
};
