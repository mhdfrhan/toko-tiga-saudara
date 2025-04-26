<div class="p-6">
    @include('components.alert')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-900">Data Supplier</h1>
            <p class="mt-1 text-sm text-neutral-600">Kelola data supplier anda</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-10 pr-4 py-2 border border-neutral-300 rounded-lg w-full focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Cari supplier...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <x-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'supplier-modal')"
                wire:click="openModal('create')">
                <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Supplier
            </x-button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg shadow-neutral-200/80 overflow-hidden border border-neutral-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Nama
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Kontak
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Alamat
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    @forelse($suppliers as $supplier)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                                {{ $supplier->nama }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ $supplier->kontak ?: '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-500">
                                {{ $supplier->alamat ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="openModal('edit', {{ $supplier->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    Edit
                                </button>
                                <button wire:click="confirmDelete({{ $supplier->id }})"
                                    class="text-red-600 hover:text-red-900">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-center text-neutral-500">
                                Tidak ada data supplier
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($suppliers->hasPages())
            <div class="px-4 py-3 border-t border-neutral-200">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>

    <!-- Form Modal -->
    <x-modal name="supplier-modal" :show="$showModal" maxWidth="md" align="center">
        <form wire:submit="save" class="p-6">
            <h2 class="text-lg font-medium text-neutral-900">
                {{ $modalType === 'create' ? 'Tambah Supplier Baru' : 'Edit Supplier' }}
            </h2>

            <div class="mt-6 space-y-6">
                <div>
                    <x-input-label for="nama" value="Nama Supplier" />
                    <x-text-input wire:model="form.nama" id="nama" type="text" class="mt-1 block w-full"
                        autocomplete="off" />
                    <x-input-error :messages="$errors->get('form.nama')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="kontak" value="Kontak" />
                    <x-text-input wire:model="form.kontak" id="kontak" type="text" class="mt-1 block w-full"
                        autocomplete="off" />
                    <x-input-error :messages="$errors->get('form.kontak')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="alamat" value="Alamat" />
                    <x-textarea wire:model="form.alamat" id="alamat" class="mt-1 block w-full" rows="3" />
                    <x-input-error :messages="$errors->get('form.alamat')" class="mt-2" />
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

    <!-- Delete Confirmation Modal -->
    <x-modal name="confirm-supplier-deletion" align="center" maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-medium text-neutral-900">
                Hapus Supplier
            </h2>

            <p class="mt-1 text-sm text-neutral-600">
                Apakah anda yakin ingin menghapus supplier ini? Data yang sudah dihapus tidak dapat dikembalikan.
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <x-button variant="secondary" x-on:click="$dispatch('close')">
                    Batal
                </x-button>

                <x-button variant="danger" type="submit" wire:click="delete">
                    Hapus Supplier
                </x-button>
            </div>
        </div>
    </x-modal>
</div>
