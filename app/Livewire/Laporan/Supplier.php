<?php

namespace App\Livewire\Laporan;

use App\Models\BarangMasuk;
use App\Models\LaporanSupplier;
use App\Models\Suppliers;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Supplier extends Component
{
    use WithPagination;

    public $search = '';
    public $dateRange = '';
    public $supplier = '';
    public $perPage = 10;
    public $showModal = false;

    #[Rule([
        'form.supplier_id' => 'required|exists:suppliers,id',
        'form.tanggal_awal' => 'required|date',
        'form.tanggal_akhir' => 'required|date|after_or_equal:form.tanggal_awal',
    ])]
    public $form = [
        'supplier_id' => '',
        'tanggal_awal' => '',
        'tanggal_akhir' => '',
    ];

    protected $messages = [
        'form.supplier_id.required' => 'Supplier wajib dipilih',
        'form.supplier_id.exists' => 'Supplier tidak valid',
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
            $data = BarangMasuk::query()
                ->where('supplier_id', $this->form['supplier_id'])
                ->whereBetween('tanggal', [
                    $this->form['tanggal_awal'],
                    $this->form['tanggal_akhir']
                ])
                ->get();

            $report = LaporanSupplier::create([
                'supplier_id' => $this->form['supplier_id'],
                'tanggal_awal' => $this->form['tanggal_awal'],
                'tanggal_akhir' => $this->form['tanggal_akhir'],
                'total_transaksi' => $data->count(),
                'total_nominal' => $data->sum('total'),
                'dibuat_oleh' => auth()->id(),
            ]);

            $this->showModal = false;
            $this->resetForm();

            return redirect()->route('laporan.supplier.show', $report);
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Terjadi kesalahan saat membuat laporan', type: 'error');
        }
    }

    public function render()
    {
        $laporan = LaporanSupplier::query()
            ->with(['supplier', 'user'])
            ->when($this->search, function ($query) {
                $query->whereHas('supplier', function ($q) {
                    $q->where('nama', 'like', "%{$this->search}%");
                });
            })
            ->when($this->supplier, function ($query) {
                $query->where('supplier_id', $this->supplier);
            })
            ->when($this->dateRange, function ($query) {
                $dates = explode(' - ', $this->dateRange);
                if (count($dates) === 2) {
                    $query->whereBetween('tanggal_awal', $dates);
                }
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.laporan.supplier', [
            'reports' => $laporan,
            'suppliers' => Suppliers::orderBy('nama')->get()
        ]);
    }

    private function resetForm()
    {
        $this->form = [
            'supplier_id' => '',
            'tanggal_awal' => '',
            'tanggal_akhir' => '',
        ];
    }
}
