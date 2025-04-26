<?php

namespace App\Livewire\Laporan;

use App\Models\LaporanPenjualan;
use App\Models\Penjualan as ModelsPenjualan;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Penjualan extends Component
{
    use WithPagination;

    public $search = '';
    public $dateRange = '';
    public $perPage = 10;
    public $showModal = false;

    #[Rule([
        'form.tanggal_awal' => 'required|date',
        'form.tanggal_akhir' => 'required|date|after_or_equal:form.tanggal_awal',
    ])]
    public $form = [
        'tanggal_awal' => '',
        'tanggal_akhir' => '',
    ];

    protected $messages = [
        'form.tanggal_awal.required' => 'Tanggal awal wajib diisi',
        'form.tanggal_awal.date' => 'Format tanggal awal tidak valid',
        'form.tanggal_akhir.required' => 'Tanggal akhir wajib diisi',
        'form.tanggal_akhir.date' => 'Format tanggal akhir tidak valid',
        'form.tanggal_akhir.after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan tanggal awal',
    ];

    public function generateReport()
    {
        $this->validate();

        try {
            $data = ModelsPenjualan::query()
                ->with(['detail.produk']) // Load relasi yang dibutuhkan
                ->whereBetween('tanggal', [
                    $this->form['tanggal_awal'],
                    $this->form['tanggal_akhir']
                ])
                ->get()
                ->map(function ($penjualan) {
                    $penjualan->laba_kotor = $penjualan->detail->sum(function ($detail) {
                        return ($detail->harga - $detail->produk->harga_beli) * $detail->jumlah;
                    });
                    return $penjualan;
                });

            $report = LaporanPenjualan::create([
                'tanggal_awal' => $this->form['tanggal_awal'],
                'tanggal_akhir' => $this->form['tanggal_akhir'],
                'total_transaksi' => $data->count(),
                'total_penjualan' => $data->sum('total'),
                'total_laba_kotor' => $data->sum('laba_kotor'),
                'dibuat_oleh' => auth()->id(),
            ]);

            $this->showModal = false;
            $this->resetForm();

            return redirect()->route('laporan.sales.show', $report);
        } catch (\Exception $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        $laporan = LaporanPenjualan::query()
            ->with(['user'])
            ->when($this->search, function ($query) {
                $query->where('id', 'like', "%{$this->search}%");
            })
            ->when($this->dateRange, function ($query) {
                $dates = explode(' - ', $this->dateRange);
                if (count($dates) === 2) {
                    $query->whereBetween('tanggal_awal', $dates);
                }
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.laporan.penjualan', [
            'reports' => $laporan
        ]);
    }

    private function resetForm()
    {
        $this->form = [
            'tanggal_awal' => '',
            'tanggal_akhir' => '',
        ];
    }
}
