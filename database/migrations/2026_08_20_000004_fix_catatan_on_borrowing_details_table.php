<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrowing_details', function (Blueprint $table) {
            $table->text('catatan')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('borrowing_details', function (Blueprint $table) {
            $table->bigInteger('catatan')->nullable(false)->change();
        });
    }
};