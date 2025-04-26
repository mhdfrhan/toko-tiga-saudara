<?php

namespace App\Livewire\LaporanSupplier;

use App\Models\LaporanSupplier;
use App\Models\BarangMasuk;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public $report;
    public $perPage = 10;

    public function mount($reportId)
    {
        $this->report = LaporanSupplier::with(['supplier', 'user'])->findOrFail($reportId);
    }

    public function print()
    {
        return redirect()->route('laporan.supplier.print', $this->report);
    }

    public function render()
    {
        $details = BarangMasuk::query()
            ->with(['detail.produk', 'user', 'supplier'])
            ->where('supplier_id', $this->report->supplier_id)
            ->whereBetween('tanggal', [
                $this->report->tanggal_awal,
                $this->report->tanggal_akhir
            ])
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.laporan-supplier.show', [
            'details' => $details
        ]);
    }
}
