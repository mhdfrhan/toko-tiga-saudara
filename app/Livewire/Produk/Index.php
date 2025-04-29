<?php

namespace App\Livewire\Produk;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Unit;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    // Search & Filter Properties
    public $search = '';
    public $category = '';
    public $sortField = 'nama';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Modal Properties
    public $modalType = 'create';
    public $selectedId;
    public $temporaryImage;

    #[Rule([
        'temporaryImage' => 'nullable|image|max:2048', 
        'form.kode_barang' => 'required|string',
        'form.nama' => 'required|string|max:255',
        'form.kategori_id' => 'required|exists:kategori,id',
        'form.unit_id' => 'required|exists:unit,id',
        'form.harga_beli' => 'required|numeric|min:0',
        'form.harga_jual' => 'required|numeric|min:0|gt:form.harga_beli',
        'form.stok' => 'required|integer|min:0'
    ])]

    protected $messages = [
        'temporaryImage.image' => 'File harus berupa gambar.',
        'temporaryImage.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        'form.kode_barang.required' => 'Kode barang wajib diisi.',
        'form.kode_barang.string' => 'Kode barang harus berupa teks.',
        'form.nama.required' => 'Nama produk wajib diisi.',
        'form.nama.string' => 'Nama produk harus berupa teks.',
        'form.nama.max' => 'Nama produk tidak boleh lebih dari 255 karakter.',
        'form.kategori_id.required' => 'Kategori wajib dipilih.',
        'form.kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
        'form.unit_id.required' => 'Satuan wajib dipilih.',
        'form.unit_id.exists' => 'Satuan yang dipilih tidak valid.',
        'form.harga_beli.required' => 'Harga beli wajib diisi.',
        'form.harga_beli.numeric' => 'Harga beli harus berupa angka.',
        'form.harga_beli.min' => 'Harga beli tidak boleh kurang dari 0.',
        'form.harga_jual.required' => 'Harga jual wajib diisi.',
        'form.harga_jual.numeric' => 'Harga jual harus berupa angka.',
        'form.harga_jual.min' => 'Harga jual tidak boleh kurang dari 0.',
        'form.harga_jual.gt' => 'Harga jual harus lebih besar dari harga beli.',
        'form.stok.required' => 'Stok wajib diisi.',
        'form.stok.integer' => 'Stok harus berupa bilangan bulat.',
        'form.stok.min' => 'Stok tidak boleh kurang dari 0.',
    ];

    public $form = [
        'gambar' => null,
        'kode_barang' => '',
        'nama' => '',
        'kategori_id' => '',
        'unit_id' => '',
        'harga_beli' => 0,
        'harga_jual' => 0,
        'stok' => 0
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function generateProductCode(): string
    {
        $prefix = 'PD';
        $year = date('y');
        $randomNum = mt_rand(1000, 9999);

        $code = $prefix . $year . $randomNum;

        while (Produk::where('kode_barang', $code)->exists()) {
            $randomNum = mt_rand(1000, 9999);
            $code = $prefix . $year . $randomNum;
        }

        return $code;
    }

    public function openModal($type, $id = null)
    {
        $this->modalType = $type;
        $this->resetForm();
        $this->resetValidation();

        if ($type === 'edit') {
            $this->selectedId = $id;
            $product = Produk::findOrFail($id);
            $this->form = [
                'gambar' => $product->gambar,
                'kode_barang' => $product->kode_barang,
                'nama' => $product->nama,
                'kategori_id' => $product->kategori_id,
                'unit_id' => $product->unit_id,
                'harga_beli' => $product->harga_beli,
                'harga_jual' => $product->harga_jual,
                'stok' => $product->stok
            ];
        } else {
            $this->form['kode_barang'] = $this->generateProductCode();
        }

        $this->dispatch('open-modal', 'product-modal');
    }

    public function confirmDelete($id)
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'confirm-product-deletion');
    }

    public function delete()
    {
        $product = Produk::findOrFail($this->selectedId);

        if ($product->gambar) {
            Storage::disk('public')->delete($product->gambar);
        }

        $product->delete();

        $this->dispatch('close-modal', 'confirm-product-deletion');
        $this->dispatch('notify', message: "Produk berhasil dihapus", type: "success");
    }

    public function save()
    {
        $this->validate();

        try {
            $data = collect($this->form)->except('gambar')->toArray();

            if ($this->temporaryImage) {
                $data['gambar'] = $this->temporaryImage->store('products', 'public');
            }

            if ($this->modalType === 'edit') {
                $product = Produk::findOrFail($this->selectedId);

                if ($this->temporaryImage && $product->gambar) {
                    Storage::disk('public')->delete($product->gambar);
                } elseif (!$this->temporaryImage) {
                    $data['gambar'] = $product->gambar;
                }

                $product->update($data);
                $message = 'Produk berhasil diperbarui';
            } else {
                Produk::create($data);
                $message = 'Produk berhasil ditambahkan';
            }

            $this->dispatch('close-modal', 'product-modal');
            $this->dispatch('notify', message: $message, type: 'success');
            $this->resetForm();
        } catch (\Exception $e) {
            if (isset($data['gambar']) && $this->temporaryImage) {
                Storage::disk('public')->delete($data['gambar']);
            }

            $this->dispatch('notify', message: 'Terjadi kesalahan saat menyimpan produk', type: 'error');
        }
    }

    public function removeImage()
    {
        if ($this->modalType === 'edit') {
            $this->form['gambar'] = null;
        }
    }

    public function resetForm()
    {
        $this->form = [
            'gambar' => null,
            'kode_barang' => '',
            'nama' => '',
            'kategori_id' => '',
            'unit_id' => '',
            'harga_beli' => 0,
            'harga_jual' => 0,
            'stok' => 0
        ];
        $this->selectedId = null;
        $this->temporaryImage = null;
    }

    public function render()
    {
        $products = Produk::query()
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
            })
            ->when($this->category, function ($query) {
                $query->where('kategori_id', $this->category);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.produk.index', [
            'products' => $products,
            'categories' => Kategori::orderBy('nama')->get(),
            'units' => Unit::orderBy('nama')->get()
        ]);
    }
}
