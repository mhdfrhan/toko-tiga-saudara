<?php

namespace App\Livewire\LaporanSales;

use App\Models\LaporanPenjualan;
use App\Models\Penjualan;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public $report;
    public $perPage = 10;

    public function mount($reportId)
    {
        $this->report = LaporanPenjualan::with(['user'])->findOrFail($reportId);
    }

    public function print()
    {
        return redirect()->route('laporan.sales.print', $this->report);
    }

    public function render()
    {
        $details = Penjualan::query()
            ->with(['user', 'detail.produk'])
            ->whereBetween('tanggal', [
                $this->report->tanggal_awal,
                $this->report->tanggal_akhir
            ])
            ->latest()
            ->paginate($this->perPage)
            ->through(function ($penjualan) {
                $penjualan->laba_kotor = $penjualan->detail->sum(function ($detail) {
                    return ($detail->harga - $detail->produk->harga_beli) * $detail->jumlah;
                });
                return $penjualan;
            });

        return view('livewire.laporan-sales.show', [
            'details' => $details
        ]);
    }
}
