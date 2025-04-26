<?php

namespace App\Livewire\BarangMasuk;

use App\Models\BarangMasuk;
use App\Models\BarangMasukDetail;
use App\Models\Produk;
use App\Models\Suppliers;
use App\Models\LaporanSupplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;

class Create extends Component
{
    use WithFileUploads, WithPagination;

    public $showProductModal = false;
    public $search = '';
    public $perPage = 10;
    public $selectedProduct = null;
    public $quantity = 1;

    #[Rule([
        'form.supplier_id' => 'required|exists:suppliers,id',
        'form.tanggal' => 'required|date',
        'form.nomor' => 'required|string|unique:barang_masuk,nomor',
        'form.catatan' => 'nullable|string'
    ])]
    public $form = [
        'supplier_id' => '',
        'tanggal' => '',
        'nomor' => '',
        'catatan' => '',
    ];

    public $items = [];
    public $productForm = [
        'kode_barang' => '',
        'nama' => '',
        'kategori_id' => '',
        'unit_id' => '',
        'harga_beli' => 0,
        'harga_jual' => 0,
        'jumlah' => 1,
    ];

    public function mount()
    {
        $this->form['tanggal'] = now()->format('Y-m-d H:i:s');
        $this->form['nomor'] = 'PO' . date('ymd') . rand(1000, 9999);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openProductModal()
    {
        $this->reset('selectedProduct', 'quantity');
        $this->showProductModal = true;
    }

    public function selectProduct(Produk $product)
    {
        $this->selectedProduct = $product;
    }

    public function addSelectedProduct()
    {
        $this->validate([
            'quantity' => 'required|integer|min:1',
            'selectedProduct' => 'required'
        ], [
            'quantity.required' => 'Jumlah wajib diisi',
            'quantity.integer' => 'Jumlah harus berupa angka',
            'quantity.min' => 'Jumlah minimal 1',
            'selectedProduct.required' => 'Pilih produk terlebih dahulu'
        ]);

        $this->items[] = [
            'product_id' => $this->selectedProduct->id,
            'kode_barang' => $this->selectedProduct->kode_barang,
            'nama' => $this->selectedProduct->nama,
            'harga' => $this->selectedProduct->harga_beli,
            'jumlah' => $this->quantity,
            'subtotal' => $this->selectedProduct->harga_beli * $this->quantity
        ];

        $this->showProductModal = false;
        $this->reset('search', 'selectedProduct', 'quantity');
        $this->dispatch('notify', message: 'Produk berhasil ditambahkan', type: 'success');
    }

    public function addItem()
    {
        $this->validate([
            'productForm.kode_barang' => 'required',
            'productForm.jumlah' => 'required|numeric|min:1'
        ]);

        $product = Produk::where('kode_barang', $this->productForm['kode_barang'])->first();

        if (!$product) {
            $this->showProductModal = true;
            return;
        }

        $this->items[] = [
            'product_id' => $product->id,
            'kode_barang' => $product->kode_barang,
            'nama' => $product->nama,
            'harga' => $product->harga_beli,
            'jumlah' => $this->productForm['jumlah'],
            'subtotal' => $product->harga_beli * $this->productForm['jumlah']
        ];

        $this->reset('productForm');
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save()
    {
        $this->validate();

        if (empty($this->items)) {
            $this->dispatch('notify', message: 'Minimal harus ada 1 produk', type: 'error');
            return;
        }

        try {
            DB::beginTransaction();

            // Create barang masuk
            $barangMasuk = BarangMasuk::create([
                'nomor' => $this->form['nomor'],
                'tanggal' => $this->form['tanggal'],
                'supplier_id' => $this->form['supplier_id'],
                'user_id' => auth()->id(),
                'catatan' => $this->form['catatan'],
                'total' => collect($this->items)->sum('subtotal')
            ]);

            // Process items
            foreach ($this->items as $item) {
                $detail = $barangMasuk->detail()->create([
                    'produk_id' => $item['product_id'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal']
                ]);

                // Update stock
                $product = Produk::find($item['product_id']);
                $product->increment('stok', $item['jumlah']);
            }

            // Update or create supplier report for current month
            $startDate = Carbon::parse($this->form['tanggal'])->startOfMonth();
            $endDate = Carbon::parse($this->form['tanggal'])->endOfMonth();

            $report = LaporanSupplier::firstOrNew([
                'supplier_id' => $this->form['supplier_id'],
                'tanggal_awal' => $startDate,
                'tanggal_akhir' => $endDate,
            ]);

            if (!$report->exists) {
                $report->fill([
                    'total_transaksi' => 1,
                    'total_nominal' => $barangMasuk->total,
                    'dibuat_oleh' => auth()->id()
                ]);
            } else {
                $report->total_transaksi += 1;
                $report->total_nominal += $barangMasuk->total;
            }

            $report->save();

            DB::commit();

            $this->dispatch('notify', message: 'Barang masuk berhasil disimpan', type: 'success');
            return redirect()->route('stock-in');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', message: 'Terjadi kesalahan saat menyimpan data', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.barang-masuk.create', [
            'suppliers' => Suppliers::orderBy('nama')->get(),
            'searchResults' => Produk::query()
                ->when($this->search, function ($query) {
                    $query->where('nama', 'like', "%{$this->search}%")
                        ->orWhere('kode_barang', 'like', "%{$this->search}%");
                })
                ->orderBy('nama')
                ->paginate($this->perPage),
            'total' => collect($this->items)->sum('subtotal')
        ]);
    }
}
