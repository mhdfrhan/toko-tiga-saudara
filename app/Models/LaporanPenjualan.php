<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPenjualan extends Model
{
    protected $table = 'laporan_penjualan';
    protected $fillable = [
        'tanggal_awal',
        'tanggal_akhir',
        'total_transaksi',
        'total_penjualan',
        'total_laba_kotor',
        'dibuat_oleh',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
