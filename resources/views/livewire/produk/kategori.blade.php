<div class="p-6">
    @include('components.alert')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-900">Kategori Produk</h1>
            <p class="mt-1 text-sm text-neutral-600">Kelola kategori produk anda disini</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-10 pr-4 py-2 border border-neutral-300 rounded-lg w-full focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Cari kategori...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <x-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'category-modal')">
                <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Kategori
            </x-button>
        </div>
    </div>

    <div class="mb-6 p-4 bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-neutral-700 mb-1">Urutkan</label>
                <x-select wire:model.live="sortField">
                    <option value="nama">Nama</option>
                    <option value="created_at">Tanggal Dibuat</option>
                    <option value="updated_at">Terakhir Diupdate</option>
                </x-select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-neutral-700 mb-1">Urutkan</label>
                <x-select wire:model.live="sortDirection">
                    <option value="asc">A-Z</option>
                    <option value="desc">Z-A</option>
                </x-select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-neutral-700 mb-1">Per Halaman</label>
                <x-select wire:model.live="perPage">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                    <option>100</option>
                </x-select>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg shadow-neutral-200/80 overflow-hidden border border-neutral-200">
        <div class="min-w-full align-middle">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Nama
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Slug
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Dibuat
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    @forelse ($categories as $category)
                        <tr wire:key="{{ $category->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                                {{ $category->nama }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ $category->slug }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ $category->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="openModal('edit', {{ $category->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    Edit
                                </button>
                                <button wire:click="confirmDelete({{ $category->id }})"
                                    class="text-red-600 hover:text-red-900">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 text-center">
                                Tidak ada data kategori
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($categories->hasPages())
            <div class="px-4 py-3 border-t border-neutral-200">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    <x-modal name="category-modal" :show="$errors->isNotEmpty()" maxWidth="lg" align="center" focusable>
        <form wire:submit="save" class="p-6">
            <h2 class="text-lg font-medium text-neutral-900">
                {{ $modalType === 'create' ? 'Tambah Kategori Baru' : 'Edit Kategori' }}
            </h2>

            <p class="mt-1 text-sm text-neutral-600">
                {{ $modalType === 'create'
                    ? 'Tambahkan kategori baru untuk mengelompokkan produk anda.'
                    : 'Edit informasi kategori yang sudah ada.' }}
            </p>

            <div class="mt-6">
                <x-input-label for="nama" value="Nama Kategori" />
                <x-text-input wire:model="form.nama" id="nama" name="nama" type="text" autocomplete="off"
                    class="mt-1 block w-full" placeholder="Masukkan nama kategori" />
                <x-input-error :messages="$errors->get('form.nama')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Batal
                </x-secondary-button>

                <x-button class="ms-3" type="submit">
                    {{ $modalType === 'create' ? 'Simpan' : 'Update' }}
                </x-button>
            </div>
        </form>
    </x-modal>

    <x-modal name="confirm-category-deletion" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-neutral-900">
                Hapus Kategori
            </h2>

            <p class="mt-1 text-sm text-neutral-600">
                Apakah anda yakin ingin menghapus kategori ini? Semua data yang terkait dengan kategori ini akan dihapus
                secara permanen.
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Batal
                </x-secondary-button>

                <x-button variant="danger" type="submit" class="ms-3" wire:click="delete" wire:loading.attr="disabled">
                    Hapus Kategori
                </x-button>
            </div>
        </div>
    </x-modal>
</div>
