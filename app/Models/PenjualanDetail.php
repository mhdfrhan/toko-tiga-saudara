<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    protected $table = 'penjualan_detail';
    protected $guarded = ['id'];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }
}
