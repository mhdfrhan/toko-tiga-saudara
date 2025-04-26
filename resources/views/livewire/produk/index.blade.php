<div class="p-6">
    @include('components.alert')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-900">Daftar Produk</h1>
            <p class="mt-1 text-sm text-neutral-600">Kelola produk anda disini</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-10 pr-4 py-2 border border-neutral-300 rounded-lg w-full focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Cari produk...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <x-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'product-modal')" wire:click="openModal('create')">
                <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Produk
            </x-button>
        </div>
    </div>

    <div class="mb-6 p-4 bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-neutral-700 mb-1">Kategori</label>
                <x-select wire:model.live="category">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->nama }}</option>
                    @endforeach
                </x-select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-neutral-700 mb-1">Urutkan</label>
                <x-select wire:model.live="sortField">
                    <option value="nama">Nama</option>
                    <option value="harga_jual">Harga</option>
                    <option value="stok">Stok</option>
                    <option value="created_at">Tanggal Dibuat</option>
                </x-select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-neutral-700 mb-1">Arah</label>
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
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Gambar
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Kode
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Nama
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Kategori
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Harga Beli
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Harga Jual
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Stok
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    @forelse ($products as $product)
                        <tr wire:key="{{ $product->id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($product->gambar)
                                    <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama }}"
                                        class="h-10 w-10 rounded-lg object-cover">
                                @else
                                    <div class="h-10 w-10 rounded-lg bg-neutral-200 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-neutral-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ $product->kode_barang }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                                {{ $product->nama }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ $product->kategori->nama }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                Rp {{ number_format($product->harga_beli, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                Rp {{ number_format($product->harga_jual, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ $product->stok }} {{ $product->unit->nama }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="openModal('edit', {{ $product->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    Edit
                                </button>
                                <button wire:click="confirmDelete({{ $product->id }})"
                                    class="text-red-600 hover:text-red-900">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 text-center">
                                Tidak ada data produk
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($products->hasPages())
            <div class="px-4 py-3 border-t border-neutral-200">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <x-modal name="product-modal" :show="$errors->isNotEmpty()" maxWidth="3xl" align="center">
        <form wire:submit="save" class="p-6">
            <h2 class="text-lg font-medium text-neutral-900">
                {{ $modalType === 'create' ? 'Tambah Produk Baru' : 'Edit Produk' }}
            </h2>
    
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Image Upload -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-neutral-700">Gambar Produk</label>
                    <div class="mt-1 flex items-center space-x-4">
                        <div class="relative">
                            @if ($temporaryImage)
                            <img src="{{ $temporaryImage->temporaryUrl() }}"
                                class="h-32 w-32 object-cover rounded-lg">
                            <button type="button" wire:click="$set('temporaryImage', null)"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        @elseif ($form['gambar'] && $modalType === 'edit')
                            <div class="relative">
                                <img src="{{ asset('storage/' . $form['gambar']) }}"
                                    class="h-32 w-32 object-cover rounded-lg">
                                <button type="button" wire:click="removeImage"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        @else
                            <label
                                class="h-32 w-32 flex items-center justify-center border-2 border-dashed border-neutral-300 rounded-lg cursor-pointer hover:border-neutral-400">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <div class="text-xs text-neutral-600">
                                        Upload Gambar
                                    </div>
                                </div>
                                <input type="file" wire:model="temporaryImage" class="sr-only" accept="image/*">
                            </label>
                        @endif
                        </div>
                        <div class="text-xs text-neutral-500">
                            Format: JPG, PNG, GIF<br>
                            Maksimal: 2MB
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('form.gambar')" class="mt-2" />
                </div>
    
                <!-- Kode Barang -->
                <div>
                    <x-input-label for="kode_barang" value="Kode Barang" />
                    <x-text-input 
                        wire:model="form.kode_barang" 
                        id="kode_barang" 
                        type="text" 
                        class="mt-1 block w-full bg-gray-100" 
                        disabled
                    />
                    <p class="mt-1 text-xs text-gray-500">Kode produk dibuat otomatis oleh sistem</p>
                </div>
    
                <!-- Nama -->
                <div>
                    <x-input-label for="nama" value="Nama Produk" />
                    <x-text-input wire:model="form.nama" id="nama" type="text" class="mt-1 block w-full"
                        placeholder="Masukkan nama produk" />
                    <x-input-error :messages="$errors->get('form.nama')" class="mt-2" />
                </div>
    
                <!-- Kategori -->
                <div>
                    <x-input-label for="kategori" value="Kategori" />
                    <x-select wire:model="form.kategori_id" id="kategori" class="mt-1 block w-full">
                        <option value="">Pilih Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->nama }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error :messages="$errors->get('form.kategori_id')" class="mt-2" />
                </div>
    
                <!-- Unit -->
                <div>
                    <x-input-label for="unit" value="Satuan" />
                    <x-select wire:model="form.unit_id" id="unit" class="mt-1 block w-full">
                        <option value="">Pilih Satuan</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error :messages="$errors->get('form.unit_id')" class="mt-2" />
                </div>
    
                <!-- Harga Beli -->
                <div>
                    <x-input-label for="harga_beli" value="Harga Beli" />
                    <x-text-input wire:model="form.harga_beli" id="harga_beli" type="number" min="0"
                        class="mt-1 block w-full" placeholder="0" />
                    <x-input-error :messages="$errors->get('form.harga_beli')" class="mt-2" />
                </div>
    
                <!-- Harga Jual -->
                <div>
                    <x-input-label for="harga_jual" value="Harga Jual" />
                    <x-text-input wire:model="form.harga_jual" id="harga_jual" type="number" min="0"
                        class="mt-1 block w-full" placeholder="0" />
                    <x-input-error :messages="$errors->get('form.harga_jual')" class="mt-2" />
                </div>
    
                <!-- Stok -->
                <div>
                    <x-input-label for="stok" value="Stok Awal" />
                    <x-text-input wire:model="form.stok" id="stok" type="number" min="0"
                        class="mt-1 block w-full" placeholder="0" />
                    <x-input-error :messages="$errors->get('form.stok')" class="mt-2" />
                </div>
            </div>
    
            <div class="mt-6 flex justify-end space-x-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Batal
                </x-secondary-button>
    
                <x-button type="submit">
                    {{ $modalType === 'create' ? 'Simpan' : 'Update' }}
                </x-button>
            </div>
        </form>
    </x-modal>
    
    <x-modal name="confirm-product-deletion" align="center" maxWidth="sm">
        <div class="p-6">
            <h2 class="text-lg font-medium text-neutral-900">
                Hapus Produk
            </h2>
    
            <p class="mt-1 text-sm text-neutral-600">
                Apakah anda yakin ingin menghapus produk ini? Semua data yang terkait dengan produk ini akan dihapus secara
                permanen.
            </p>
    
            <div class="mt-6 flex justify-end">
                <x-button variant="secondary" x-on:click="$dispatch('close')">
                    Batal
                </x-button>
    
                <x-button variant="danger" type="submit" class="ms-3" wire:click="delete" wire:loading.attr="disabled">
                    Hapus Produk
                </x-button>
            </div>
        </div>
    </x-modal>
</div>

