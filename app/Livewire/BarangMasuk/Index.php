<?php

namespace App\Livewire\BarangMasuk;

use App\Models\BarangMasuk;
use App\Models\Suppliers;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $dateRange = '';
    public $supplier = '';
    public $perPage = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $barangMasuk = BarangMasuk::query()
            ->with(['supplier', 'user'])
            ->when($this->search, function($query) {
                $query->where('nomor', 'like', "%{$this->search}%")
                    ->orWhereHas('supplier', function($q) {
                        $q->where('nama', 'like', "%{$this->search}%");
                    });
            })
            ->when($this->supplier, function($query) {
                $query->where('supplier_id', $this->supplier);
            })
            ->when($this->dateRange, function($query) {
                $dates = explode(' - ', $this->dateRange);
                if (count($dates) === 2) {
                    $query->whereBetween('tanggal', $dates);
                }
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.barang-masuk.index', [
            'purchases' => $barangMasuk,
            'suppliers' => Suppliers::orderBy('nama')->get()
        ]);
    }
}