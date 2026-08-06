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
        if (!Schema::hasTable('borrowing_details')) {
            Schema::create('borrowing_details', function (Blueprint $table) {
                $table->string('id', 36)->primary();
                $table->string('borrowing_id', 36);
                $table->string('item_id', 36);
                $table->string('kondisi_barang');
                $table->bigInteger('denda');
                $table->integer('jumlah');
                $table->bigInteger('catatan');
                $table->timestamps();
                $table->softDeletes();
                $table->string('created_by', 36)->nullable();
                $table->string('updated_by', 36)->nullable();
                $table->string('deleted_by', 36)->nullable();

                $table->foreign('borrowing_id')->references('id')->on('borrowings')->cascadeOnDelete();
                $table->foreign('item_id')->references('id')->on('items')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowing_details');
    }
};
