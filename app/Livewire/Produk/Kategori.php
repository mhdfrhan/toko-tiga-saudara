<?php

namespace App\Livewire\Produk;

use App\Models\Kategori as Category;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;

class Kategori extends Component
{
    use WithPagination;

    public $modalType = 'create';
    public $search = '';
    public $sortField = 'nama';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $selectedId;

    #[Rule(['required', 'max:255'])]
    public $form = [
        'nama' => '',
    ];

    public function confirmDelete($id)
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'confirm-category-deletion');
    }

    public function resetForm()
    {
        $this->form = [
            'nama' => '',
        ];
        $this->selectedId = null;
    }

    public function openModal($type, $id = null)
    {
        $this->modalType = $type;
        $this->resetForm();

        if ($type === 'edit') {
            $this->selectedId = $id;
            $category = Category::find($id);
            $this->form['nama'] = $category->nama;
        }

        $this->dispatch('open-modal', 'category-modal');
    }

    public function save()
    {
        try {
            $this->validate();
        } catch (ValidationException $e) {
            $this->dispatch('notify', message: $e->validator->errors()->first(), type: 'error');
            return;
        }

        if ($this->modalType === 'create') {
            Category::create([
                'nama' => $this->form['nama'],
                'slug' => Str::slug($this->form['nama'])
            ]);

            $this->dispatch('notify', message: 'Kategori berhasil ditambahkan', type: 'success');
        } else {
            $category = Category::find($this->selectedId);
            $category->update([
                'nama' => $this->form['nama'],
                'slug' => Str::slug($this->form['nama'])
            ]);
            $this->dispatch('notify', message: 'Kategori berhasil diperbarui', type: 'success');
        }

        $this->dispatch('close-modal', 'category-modal');
        $this->resetForm();
    }

    public function delete()
    {
        Category::destroy($this->selectedId);
        $this->dispatch('close-modal', 'confirm-category-deletion');
        $this->dispatch('notify', message: 'Kategori berhasil dihapus', type: 'success');
    }

    public function render()
    {
        $categories = Category::where('nama', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.produk.kategori', [
            'categories' => $categories
        ]);
    }
}
