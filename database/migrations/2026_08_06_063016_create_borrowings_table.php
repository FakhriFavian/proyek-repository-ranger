<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->string('id', 36)->primary();

            $table->string('user_id', 36);

            $table->dateTime('jam_mulai');
            $table->dateTime('jam_selesai');

            $table->string('status');

            $table->text('catatan_admin')->nullable();
            $table->text('alasan_penolakan')->nullable();

            $table->string('approved_by', 36)->nullable();
            $table->dateTime('tanggal_approval')->nullable();

            $table->string('diproses_oleh', 36)->nullable();

            $table->dateTime('tanggal_kembali')->nullable();

            $table->tinyInteger('is_active')->default(1);

            $table->timestamps();
            $table->softDeletes();

            $table->string('created_by', 36)->nullable();
            $table->string('updated_by', 36)->nullable();
            $table->string('deleted_by', 36)->nullable();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};