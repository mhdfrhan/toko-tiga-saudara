<?php

namespace App\Livewire\BarangMasuk;

use App\Models\BarangMasuk;
use Livewire\Component;

class Show extends Component
{
    public $purchaseId;
    public $barangMasuk;

    public function mount($purchaseId)
    {
        $this->purchaseId = $purchaseId;
        $this->barangMasuk = BarangMasuk::with(['supplier', 'user', 'detail.produk.unit'])
            ->findOrFail($purchaseId);
    }

    public function render()
    {
        return view('livewire.barang-masuk.show');
    }
}