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
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('gambar')->nullable();
            $table->string('kode_barang')->unique();
            $table->string('nama');
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('unit')->onDelete('cascade');
            $table->decimal('harga_beli', 10, 2);
            $table->decimal('harga_jual', 10, 2);
            $table->integer('stok')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
