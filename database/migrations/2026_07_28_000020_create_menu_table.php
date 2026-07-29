<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('menu');
            $table->string('module');
            $table->string('routing');
            $table->tinyInteger('is_tampil');
            $table->string('icon');
            $table->integer('urutan');
            $table->string('parent_id', 64)->nullable();
            $table->tinyInteger('level');
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by', 36)->nullable();
            $table->string('updated_by', 36)->nullable();
            $table->string('deleted_by', 36)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
