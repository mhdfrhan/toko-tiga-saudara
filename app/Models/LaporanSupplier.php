<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanSupplier extends Model
{
    protected $table = 'laporan_suppliers';
    
    protected $fillable = [
        'supplier_id',
        'tanggal_awal',
        'tanggal_akhir',
        'total_transaksi',
        'total_nominal',
        'dibuat_oleh'
    ];

    protected $casts = [
        'tanggal_awal' => 'date',
        'tanggal_akhir' => 'date',
        'total_nominal' => 'decimal:2'
    ];

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class, 'supplier_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}