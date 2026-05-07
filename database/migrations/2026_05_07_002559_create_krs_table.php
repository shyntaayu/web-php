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
        Schema::create('krs', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel mahasiswas
            $table->foreignId('mahasiswa_id')->constrained()->onDelete('cascade');

            // Foreign key ke tabel mata_kuliahs (kita beri nama matkul_id agar lebih rapi)
            $table->foreignId('matkul_id')->constrained('mata_kuliahs')->onDelete('cascade');

            // Kolom tambahan di tabel pivot
            $table->string('nilai', 2)->nullable(); // A, B, C, dst
            $table->integer('semester')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('krs');
    }
};
