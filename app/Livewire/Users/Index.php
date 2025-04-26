<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $role = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    public $modalType = 'create';
    public $selectedId;
    public $selectedUser;

    public $form = [
        'name' => '',
        'username' => '',
        'password' => '',
        'role' => '',
    ];

    protected function rules()
    {
        return [
            'form.name' => 'required|string|max:255',
            'form.username' => 'required|string|max:255|unique:users,username,' . $this->selectedId,
            'form.password' => $this->modalType === 'create' ? 'required|min:8' : 'nullable|min:8',
            'form.role' => 'required|in:admin,kasir',
        ];
    }

    protected $messages = [
        'form.name.required' => 'Nama wajib diisi',
        'form.name.string' => 'Nama harus berupa teks',
        'form.name.max' => 'Nama maksimal 255 karakter',
        'form.username.required' => 'Username wajib diisi',
        'form.username.string' => 'Username harus berupa teks',
        'form.password.required' => 'Password wajib diisi',
        'form.password.min' => 'Password minimal 8 karakter',
        'form.role.required' => 'Role wajib dipilih',
        'form.role.in' => 'Role tidak valid',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->form = [
            'name' => '',
            'username' => '',
            'password' => '',
            'role' => '',
        ];
        $this->selectedId = null;
        $this->selectedUser = null;
        $this->resetValidation();
    }

    public function openModal($type = 'create', $id = null)
    {
        $this->modalType = $type;
        $this->resetForm();

        if ($type === 'edit') {
            $this->selectedId = $id;
            $user = User::findOrFail($id);
            $this->form = [
                'name' => $user->name,
                'username' => $user->username,
                'password' => '',
                'role' => $user->role,
            ];
            $this->dispatch('open-modal', 'user-modal');
        } elseif ($type === 'detail') {
            $this->selectedUser = User::with(['barangMasuk', 'penjualan'])->findOrFail($id);
            $this->dispatch('open-modal', 'detail-modal');
        } else {
            $this->dispatch('open-modal', 'user-modal');
        }
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->selectedUser = null;
        $this->dispatch('close-modal', 'user-modal');
        $this->dispatch('close-modal', 'detail-modal');
    }

    public function save()
    {
        $this->validate();

        try {
            $data = collect($this->form)->except('password')->toArray();

            if ($this->modalType === 'edit') {
                $user = User::findOrFail($this->selectedId);

                if ($this->form['password']) {
                    $data['password'] = Hash::make($this->form['password']);
                }

                $user->update($data);
                $message = 'User berhasil diperbarui';
            } else {
                $data['password'] = Hash::make($this->form['password']);
                User::create($data);
                $message = 'User berhasil ditambahkan';
            }

            $this->dispatch('notify', message: $message, type: 'success');
            $this->dispatch('close-modal', 'user-modal');
            $this->resetForm();
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Terjadi kesalahan saat menyimpan data', type: 'error');
        }
    }

    public function confirmDelete($id)
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'confirm-user-deletion');
    }

    public function delete()
    {
        try {
            $user = User::findOrFail($this->selectedId);

            if ($user->role === 'owner') {
                throw new \Exception('Tidak dapat menghapus akun owner');
            }

            if ($user->barangMasuk()->exists() || $user->penjualan()->exists()) {
                throw new \Exception('User tidak dapat dihapus karena masih memiliki transaksi terkait');
            }

            $user->delete();

            $this->dispatch('notify', message: 'User berhasil dihapus', type: 'success');
            $this->dispatch('close-modal', 'confirm-user-deletion');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('username', 'like', "%{$this->search}%");
                });
            })
            ->when($this->role, function ($query) {
                $query->where('role', $this->role);
            })
            ->orderByRaw("id = ? DESC", [auth()->id()]) 
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.users.index', [
            'users' => $users
        ]);
    }
}
