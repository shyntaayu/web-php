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
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();                          // bigint auto increment
            $table->string('nim', 15)->unique();   // varchar(15) unique
            $table->string('nama', 100);
            $table->string('email')->unique();
            $table->enum('jurusan', ['SI', 'TI', 'MI', 'AK']);
            $table->decimal('ipk', 3, 2)->default(0.00);
            $table->integer('semester')->default(1);
            $table->boolean('aktif')->default(true);
            $table->string('foto')->nullable();    // nullable = boleh kosong
            $table->text('alamat')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();                  // created_at & updated_at
            $table->softDeletes();                 // deleted_at (soft delete)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
