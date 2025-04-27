<?php

namespace App\Livewire\Kasir;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $perPage = 24;

    public $cart = [];
    public $total = 0;
    public $paymentAmount = '';
    public $change = 0;
    public $paymentMethod = 'cash';
    public $qrisImage = null;
    public $lastSaleId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function addToCart(Produk $product, $quantity = 1)
    {
        if (!isset($this->cart[$product->id])) {
            $this->cart[$product->id] = [
                'id' => $product->id,
                'gambar' => $product->gambar,
                'kode' => $product->kode_barang,
                'nama' => $product->nama,
                'harga' => $product->harga_jual,
                'unit' => $product->unit->nama,
                'quantity' => $quantity,
                'subtotal' => $product->harga_jual * $quantity
            ];
        } else {
            $this->cart[$product->id]['quantity'] += $quantity;
            $this->cart[$product->id]['subtotal'] = $this->cart[$product->id]['harga'] * $this->cart[$product->id]['quantity'];
        }

        $this->calculateTotal();
    }

    public function updateQuantity($productId, $quantity)
    {
        if ($quantity <= 0) {
            unset($this->cart[$productId]);
        } else {
            $this->cart[$productId]['quantity'] = $quantity;
            $this->cart[$productId]['subtotal'] = $this->cart[$productId]['harga'] * $quantity;
        }

        $this->calculateTotal();
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
        $this->calculateTotal();
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->total = 0;
        $this->resetPayment();
    }

    public function calculateTotal()
    {
        $this->total = collect($this->cart)->sum('subtotal');
        $this->calculateChange();
    }

    public function updatedPaymentAmount($value)
    {
        $this->paymentAmount = $value;
        $this->calculateChange();
    }

    public function calculateChange()
    {
        $this->change = (int)$this->paymentAmount - $this->total;
    }

    public function resetPayment()
    {
        $this->paymentAmount = 0;
        $this->change = 0;
        $this->paymentMethod = 'cash';
        $this->qrisImage = null;
    }

    public function processPayment()
    {
        try {
            DB::beginTransaction();

            if (empty($this->cart)) {
                throw new \Exception('Keranjang kosong');
            }

            if ($this->paymentMethod === 'cash') {
                if ($this->paymentAmount < $this->total) {
                    $this->dispatch('notify', message: 'Jumlah bayar tidak cukup', type: 'error');
                    return;
                }
            } else {
                $this->paymentAmount = $this->total;
                $this->change = 0;
            }

            $getLastId = Penjualan::latest()->first();
            $getLastId = $getLastId ? $getLastId->id + 1 : 1;

            $sale = Penjualan::create([
                'nomor' => 'INV-' . $getLastId . date('Ymd-His'),
                'tanggal' => now(),
                'total' => $this->total,
                'metode_pembayaran' => $this->paymentMethod,
                'jumlah_bayar' => $this->paymentAmount,
                'kembalian' => $this->change,
                'user_id' => auth()->id()
            ]);

            foreach ($this->cart as $item) {
                $sale->detail()->create([
                    'produk_id' => $item['id'],
                    'jumlah' => $item['quantity'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal']
                ]);

                $product = Produk::find($item['id']);
                $product->decrement('stok', $item['quantity']);
            }

            DB::commit();

            $this->lastSaleId = $sale->id;
            $this->dispatch('open-modal', 'success-modal');
            $this->cart = [];
            $this->total = 0;
            $this->resetPayment();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        }
    }

    public function closeSuccessModal()
    {
        $this->dispatch('close-modal', 'success-modal');
        $this->lastSaleId = null;
    }

    public function render()
    {
        $products = Produk::query()
            ->with(['unit', 'kategori'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama', 'like', "%{$this->search}%")
                        ->orWhere('kode_barang', 'like', "%{$this->search}%");
                });
            })
            ->when($this->category, function ($query) {
                $query->where('kategori_id', $this->category);
            })
            ->orderBy('nama')
            ->paginate($this->perPage);

        return view('livewire.kasir.index', [
            'products' => $products,
            'categories' => Kategori::orderBy('nama')->get()
        ]);
    }
}
