<?php

namespace App\Livewire\Supplier;

use App\Models\Suppliers;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'nama';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $showModal = false;
    public $modalType = 'create';
    public $selectedId;

    #[Rule([
        'form.nama' => 'required|string|max:255',
        'form.kontak' => 'nullable|string|max:255',
        'form.alamat' => 'nullable|string'
    ])]
    public $form = [
        'nama' => '',
        'kontak' => '',
        'alamat' => ''
    ];

    protected $messages = [
        'form.nama.required' => 'Nama supplier wajib diisi',
        'form.nama.string' => 'Nama supplier harus berupa teks',
        'form.nama.max' => 'Nama supplier maksimal 255 karakter',
        'form.kontak.string' => 'Kontak harus berupa teks',
        'form.kontak.max' => 'Kontak maksimal 255 karakter',
        'form.alamat.string' => 'Alamat harus berupa teks',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($type = 'create', $id = null)
    {
        $this->modalType = $type;
        $this->selectedId = $id;
        $this->resetForm();

        if ($type === 'edit') {
            $supplier = Suppliers::findOrFail($id);
            $this->form = $supplier->only(['nama', 'kontak', 'alamat']);
        }

        $this->dispatch('open-modal', 'supplier-modal'); 
    }

    public function confirmDelete($id)
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'confirm-supplier-deletion');
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->modalType === 'edit') {
                $supplier = Suppliers::findOrFail($this->selectedId);
                $supplier->update($this->form);
                $message = 'Supplier berhasil diperbarui';
            } else {
                Suppliers::create($this->form);
                $message = 'Supplier berhasil ditambahkan';
            }

            $this->dispatch('notify', message: $message, type: 'success');
            $this->dispatch('close-modal', 'supplier-modal');
            $this->resetForm();
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Terjadi kesalahan saat menyimpan data', type: 'error');
        }
    }

    public function delete()
    {
        try {
            if (!$this->selectedId) {
                throw new \Exception('Data supplier tidak ditemukan');
            }

            $supplier = Suppliers::findOrFail($this->selectedId);

            if ($supplier->barangMasuk()->exists()) {
                throw new \Exception('Supplier tidak dapat dihapus karena masih memiliki transaksi terkait');
            }

            $supplier->delete();

            $this->dispatch('notify', message: 'Supplier berhasil dihapus', type: 'success');
            $this->dispatch('close-modal', 'confirm-supplier-deletion');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        }
    }

    public function resetForm()
    {
        $this->form = [
            'nama' => '',
            'kontak' => '',
            'alamat' => ''
        ];
        $this->resetValidation();
    }

    public function render()
    {
        $suppliers = Suppliers::query()
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', "%{$this->search}%")
                    ->orWhere('kontak', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.supplier.index', [
            'suppliers' => $suppliers
        ]);
    }
}
