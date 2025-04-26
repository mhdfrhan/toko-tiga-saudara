<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
    protected $table = 'suppliers';

    protected $guarded = ['id'];

    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class, 'supplier_id');
    }

    public function laporan()
    {
        return $this->hasMany(LaporanSupplier::class);
    }
}
