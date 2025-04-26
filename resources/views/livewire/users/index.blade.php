<div class="p-6">
    @include('components.alert')

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-900">Kelola User</h1>
            <p class="mt-1 text-sm text-neutral-600">Kelola akses pengguna aplikasi</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <x-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'user-modal')"
                wire:click="openModal('create')">
                <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah User
            </x-button>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="mb-6 p-4 bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-neutral-700 mb-1">Cari</label>
                <x-text-input wire:model.live.debounce.300ms="search" type="text" class="w-full"
                    placeholder="Cari nama atau username..." />
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-neutral-700 mb-1">Role</label>
                <x-select wire:model.live="role" class="w-full">
                    <option value="">Semua Role</option>
                    <option value="owner">Owner</option>
                    <option value="admin">Admin</option>
                    <option value="kasir">Kasir</option>
                </x-select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-neutral-700 mb-1">Urutkan</label>
                <x-select wire:model.live="sortField" class="w-full">
                    <option value="name">Nama</option>
                    <option value="created_at">Tanggal Dibuat</option>
                </x-select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-neutral-700 mb-1">Arah</label>
                <x-select wire:model.live="sortDirection" class="w-full">
                    <option value="asc">A-Z</option>
                    <option value="desc">Z-A</option>
                </x-select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-neutral-700 mb-1">Per Halaman</label>
                <x-select wire:model.live="perPage" class="w-full">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                    <option>100</option>
                </x-select>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    @forelse($users as $user)
                        <tr class="{{ $user->id === auth()->id() ? 'bg-neutral-50' : '' }} hover:bg-neutral-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div
                                            class="h-10 w-10 rounded-full {{ $user->id === auth()->id() ? 'bg-indigo-100' : 'bg-neutral-200' }} flex items-center justify-center">
                                            <span
                                                class="{{ $user->id === auth()->id() ? 'text-indigo-600' : 'text-neutral-600' }} font-medium text-sm">
                                                {{ substr($user->name, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div
                                            class="text-sm font-medium {{ $user->id === auth()->id() ? 'text-indigo-900' : 'text-neutral-900' }}">
                                            {{ $user->name }}
                                            @if ($user->id === auth()->id())
                                                <span class="ml-2 text-xs font-medium text-indigo-600">(Anda)</span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-neutral-500">Bergabung
                                            {{ $user->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $user->role === 'owner' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $user->role === 'admin' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $user->role === 'kasir' ? 'bg-orange-100 text-orange-800' : '' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <button wire:click="openModal('detail', {{ $user->id }})"
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                    Detail
                                </button>
                                @if ($user->role !== 'owner')
                                    <button wire:click="openModal('edit', {{ $user->id }})"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        Edit
                                    </button>
                                    <button wire:click="confirmDelete({{ $user->id }})"
                                        class="text-red-600 hover:text-red-900">
                                        Hapus
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-neutral-500">
                                Tidak ada data user
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="px-4 py-3 border-t border-neutral-200">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    <x-modal name="user-modal">
        <form wire:submit="save" class="p-6">
            <h2 class="text-lg font-medium text-neutral-900">
                {{ $modalType === 'create' ? 'Tambah User Baru' : 'Edit User' }}
            </h2>

            <div class="mt-6 space-y-6">
                <div>
                    <x-input-label for="name" value="Nama" />
                    <x-text-input wire:model="form.name" id="name" type="text" class="mt-1 block w-full"
                        autocomplete="off" />
                    <x-input-error :messages="$errors->get('form.name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="username" value="Username" />
                    <x-text-input wire:model="form.username" id="username" type="text" class="mt-1 block w-full"
                        autocomplete="off" />
                    <x-input-error :messages="$errors->get('form.username')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password"
                        value="{{ $modalType === 'create' ? 'Password' : 'Password (Kosongkan jika tidak ingin mengubah)' }}" />
                    <x-text-input wire:model="form.password" id="password" type="password"
                        class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="role" value="Role" />
                    <x-select wire:model="form.role" id="role" class="mt-1 block w-full">
                        <option value="">Pilih Role</option>
                        <option value="admin">Admin</option>
                        <option value="kasir">Kasir</option>
                    </x-select>
                    <x-input-error :messages="$errors->get('form.role')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-button variant="secondary" x-on:click="$dispatch('close')">
                    Batal
                </x-button>

                <x-button type="submit">
                    {{ $modalType === 'create' ? 'Simpan' : 'Update' }}
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- Detail Modal -->
    <x-modal name="detail-modal">
        @if ($selectedUser)
            <div class="p-6">
                <h2 class="text-lg font-medium text-neutral-900">Detail User</h2>

                <div class="mt-6">
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-t-lg">
                        <dt class="text-sm font-medium text-neutral-500">Nama Lengkap</dt>
                        <dd class="mt-1 text-sm text-neutral-900 sm:col-span-2 sm:mt-0">{{ $selectedUser->name }}</dd>
                    </div>

                    <div class="bg-neutral-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-neutral-500">Role</dt>
                        <dd class="mt-1 text-sm text-neutral-900 sm:col-span-2 sm:mt-0">
                            {{ ucfirst($selectedUser->role) }}
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-neutral-500">Bergabung</dt>
                        <dd class="mt-1 text-sm text-neutral-900 sm:col-span-2 sm:mt-0">
                            {{ $selectedUser->created_at->format('d F Y H:i') }}</dd>
                    </div>
                    <div class="bg-neutral-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-neutral-500">Total Transaksi Masuk</dt>
                        <dd class="mt-1 text-sm text-neutral-900 sm:col-span-2 sm:mt-0">
                            {{ $selectedUser->barangMasuk->count() }} transaksi</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-b-lg">
                        <dt class="text-sm font-medium text-neutral-500">Total Penjualan</dt>
                        <dd class="mt-1 text-sm text-neutral-900 sm:col-span-2 sm:mt-0">
                            {{ $selectedUser->penjualan->count() }} transaksi</dd>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-button variant="secondary" x-on:click="$dispatch('close')">
                        Tutup
                    </x-button>
                </div>
            </div>
        @endif
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal name="confirm-user-deletion" align="center" maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-medium text-neutral-900">
                Hapus User
            </h2>

            <p class="mt-1 text-sm text-neutral-600">
                Apakah anda yakin ingin menghapus user ini? Data yang sudah dihapus tidak dapat dikembalikan.
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <x-button variant="secondary" x-on:click="$dispatch('close')">
                    Batal
                </x-button>

                <x-button variant="danger" type="submit" wire:click="delete">
                    Hapus User
                </x-button>
            </div>
        </div>
    </x-modal>
</div>
