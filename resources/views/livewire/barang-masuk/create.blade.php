<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-900">Tambah Barang Masuk</h1>
            <p class="mt-1 text-sm text-neutral-600">Catat pembelian barang dari supplier</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200 p-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="nomor" value="Nomor PO" />
                    <x-text-input wire:model="form.nomor" id="nomor" type="text"
                        class="mt-1 block w-full bg-gray-100" readonly />
                </div>
                <div>
                    <x-input-label for="tanggal" value="Tanggal" />
                    <x-text-input wire:model="form.tanggal" id="tanggal" type="date" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('form.tanggal')" class="mt-2" />
                </div>
                <div class="col-span-2">
                    <x-input-label for="supplier" value="Supplier" />
                    <x-select wire:model="form.supplier_id" id="supplier" class="mt-1 block w-full">
                        <option value="">Pilih Supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error :messages="$errors->get('form.supplier_id')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200 p-4">
            <x-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'product-modal')"
                wire:click="openProductModal" class="w-full justify-center">
                <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Cari Produk
            </x-button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200 overflow-hidden">
        <table class="min-w-full divide-y divide-neutral-200">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Kode
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Nama
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Harga
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">
                        Jumlah</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">
                        Subtotal</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
                @forelse($items as $index => $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item['kode_barang'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item['nama'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                            {{ number_format($item['harga'], 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">{{ $item['jumlah'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                            {{ number_format($item['subtotal'], 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                            <button wire:click="removeItem({{ $index }})"
                                class="text-red-600 hover:text-red-900">
                                Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-neutral-500">
                            Belum ada produk ditambahkan
                        </td>
                    </tr>
                @endforelse
                @if (count($items) > 0)
                    <tr class="bg-neutral-50">
                        <td colspan="4" class="px-6 py-4 text-right font-medium">Total</td>
                        <td class="px-6 py-4 text-right font-medium">
                            {{ number_format($total, 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-end space-x-3">
        <a href="{{ route('stock-in') }}">
            <x-button variant="secondary">
                Batal
            </x-button>
        </a>
        <x-button type="submit" wire:click="save">
            Simpan
        </x-button>
    </div>

    <x-modal name="product-modal" :show="$showProductModal" maxWidth="3xl">
        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-lg font-medium text-neutral-900">Pilih Produk</h2>
                <p class="mt-1 text-sm text-neutral-600">
                    Cari dan pilih produk yang akan ditambahkan
                </p>
            </div>
    
            <div class="mb-6">
                <x-text-input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    class="w-full"
                    placeholder="Cari berdasarkan nama atau kode produk..." 
                />
            </div>
    
            <div class="border border-neutral-200 rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase">Harga Beli</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase">Stok</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        @forelse($searchResults as $product)
                            <tr class="{{ $selectedProduct?->id === $product->id ? 'bg-neutral-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $product->kode_barang }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $product->nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                    {{ number_format($product->harga_beli, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">{{ $product->stok }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <x-button 
                                        wire:click="selectProduct({{ $product->id }})" 
                                        variant="{{ $selectedProduct?->id === $product->id ? 'secondary' : 'primary' }}"
                                        size="sm"
                                    >
                                        {{ $selectedProduct?->id === $product->id ? 'Terpilih' : 'Pilih' }}
                                    </x-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-neutral-500">
                                    {{ $search ? 'Produk tidak ditemukan' : 'Tidak ada produk' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
    
            @if($searchResults->hasPages())
                <div class="mt-4">
                    {{ $searchResults->links() }}
                </div>
            @endif
    
            <div class="mt-6 flex items-center justify-between">
                @if($selectedProduct)
                    <div class="flex items-center gap-4">
                        <label class="text-sm font-medium text-neutral-700">Jumlah:</label>
                        <x-text-input 
                            wire:model="quantity" 
                            type="number" 
                            min="1"
                            class="w-24"
                        />
                    </div>
            
                    <div class="flex gap-3">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            Batal
                        </x-secondary-button>
            
                        <x-button wire:click="addSelectedProduct">
                            Tambahkan
                        </x-button>
                    </div>
                @else
                    <div></div>
                    <div class="flex gap-3">
                        <x-button variant="success" x-on:click="$dispatch('close')">
                            Selesai
                        </x-button>
                    </div>
                @endif
            </div>
        </div>
    </x-modal>
</div>
