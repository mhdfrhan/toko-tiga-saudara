<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    protected $table = 'barang_masuk';
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class);
    }

    public function detail()
    {
        return $this->hasMany(BarangMasukDetail::class, 'barang_masuk_id');
    }
}
