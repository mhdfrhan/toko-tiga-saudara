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
        Schema::create('laporan_suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->date('tanggal_awal');
            $table->date('tanggal_akhir');
            $table->integer('total_transaksi')->default(0);
            $table->decimal('total_nominal', 12, 2)->default(0);
            $table->foreignId('dibuat_oleh')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_suppliers');
    }
};
