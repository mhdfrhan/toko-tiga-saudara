<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasukDetail extends Model
{
    protected $table = 'barang_masuk_detail';
    protected $guarded = ['id'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class);
    }
}

