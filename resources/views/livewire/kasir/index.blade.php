<div x-data="{ mobileCartOpen: false }">

    @include('components.alert')
    
    <div class="mb-6 p-4 bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-neutral-700 mb-1">Cari Produk</label>
                <x-text-input wire:model.live.debounce.300ms="search" type="text" class="w-full"
                    placeholder="Cari nama atau kode produk..." />
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1">Kategori</label>
                <x-select wire:model.live="category" class="w-full">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->nama }}</option>
                    @endforeach
                </x-select>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap -mx-4">
        <div class="w-full p-4 lg:w-4/6 lg:p-4">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                @forelse($products as $product)
                    <div
                        class="bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200 overflow-hidden hover:shadow-xl transition-shadow">
                        <div class="aspect-square relative">
                            @if ($product->gambar)
                                <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama }}"
                                    class="w-full h-full object-cover" loading="lazy">
                            @else
                                <div class="w-full h-full bg-neutral-100 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-neutral-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <div class="text-xs text-neutral-500 mb-1">{{ $product->kode_barang }}</div>
                            <h3 class="font-medium text-neutral-900 mb-1 truncate">{{ $product->nama }}</h3>
                            <div class="text-sm text-neutral-600 mb-3">
                                Rp {{ number_format($product->harga_jual, 0, ',', '.') }}/{{ $product->unit->nama }}
                            </div>
                            <x-button wire:click="addToCart({{ $product }})" class="w-full justify-center"
                                size="sm">
                                Tambah
                            </x-button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full p-8 text-center">
                        <div class="text-neutral-500">Tidak ada produk ditemukan</div>
                    </div>
                @endforelse
            </div>

            @if ($products->hasPages())
                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

        <div class="hidden lg:block lg:w-2/6 p-4">
            <div class="bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200 sticky top-6">
                <div class="p-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-neutral-900">Keranjang Belanja</h2>
                </div>

                <div class="divide-y divide-neutral-200">
                    <div class="max-h-[calc(100vh-400px)] overflow-y-auto">
                        @forelse($cart as $item)
                            <div class="p-4">
                                <div class="flex justify-between items-start gap-4">
                                    <div class="flex-shrink-0 w-16 h-16 bg-neutral-100 rounded-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $item['gambar']) }}" alt="{{ $item['nama'] }}"
                                            class="w-full h-full object-cover" loading="lazy">
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-neutral-900">{{ $item['nama'] }}</h4>
                                        <div class="text-sm text-neutral-500">{{ $item['kode'] }}</div>
                                        <div class="text-sm font-medium text-neutral-900">
                                            Rp {{ number_format($item['harga'], 0, ',', '.') }}/{{ $item['unit'] }}
                                        </div>
                                    </div>
                                    <button wire:click="removeFromCart({{ $item['id'] }})"
                                        class="text-red-500 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="mt-2 flex items-center gap-4">
                                    <div class="flex items-center border border-neutral-200 rounded-md">
                                        <button
                                            wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})"
                                            class="p-1 text-neutral-500 hover:text-neutral-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4" />
                                            </svg>
                                        </button>
                                        <input type="number" wire:model.live="cart.{{ $item['id'] }}.quantity"
                                            class="w-16 text-center border-0 p-0 focus:ring-0" min="1">
                                        <button
                                            wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})"
                                            class="p-1 text-neutral-500 hover:text-neutral-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="flex-1 text-right font-medium">
                                        Rp. {{ number_format($item['subtotal'], 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-neutral-500">
                                Keranjang masih kosong
                            </div>
                        @endforelse
                    </div>

                    <div class="p-4">
                        <div class="flex justify-between items-center mb-4">
                            <div class="font-medium text-neutral-900">Total</div>
                            <div class="text-lg font-semibold text-neutral-900">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </div>
                        </div>

                        @if (count($cart) > 0)
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 mb-1">Metode
                                        Pembayaran</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <button wire:click="$set('paymentMethod', 'cash')"
                                            class="p-2 rounded-lg border-2 text-center {{ $paymentMethod === 'cash' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-neutral-200 text-neutral-600 hover:border-neutral-300' }}">
                                            <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Tunai
                                        </button>
                                        <button wire:click="$set('paymentMethod', 'qris')"
                                            class="p-2 rounded-lg border-2 text-center {{ $paymentMethod === 'qris' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-neutral-200 text-neutral-600 hover:border-neutral-300' }}">
                                            <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                            </svg>
                                            QRIS
                                        </button>
                                    </div>
                                </div>

                                @if ($paymentMethod === 'cash')
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 mb-1">Jumlah
                                            Bayar</label>
                                        <x-text-input wire:model.live="paymentAmount" type="number"
                                            class="w-full text-right" min="0" />
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <div class="font-medium text-neutral-900">Kembalian</div>
                                        <div
                                            class="text-lg font-semibold {{ $change > 0 ? 'text-green-600' : 'text-neutral-900' }}">
                                            Rp {{ number_format($change, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center p-4 border-2 border-dashed border-neutral-300 rounded-lg">
                                        <div class="text-sm text-neutral-600 mb-2">Silahkan scan QR Code berikut</div>
                                        <img src="{{ asset('assets/qris.png') }}" alt="QRIS"
                                            class="mx-auto max-w-[200px]">
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6 grid grid-cols-2 gap-3">
                                <x-button wire:click="clearCart" variant="secondary" class="justify-center">
                                    Batal
                                </x-button>
                                <x-button wire:click="processPayment" class="justify-center" :disabled="count($cart) === 0 || ($paymentMethod === 'cash' && $paymentAmount < $total)">
                                    Bayar
                                </x-button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:hidden fixed bottom-6 right-6 z-30">
        <button @click="mobileCartOpen = true"
            class="flex items-center justify-center w-16 h-16 bg-indigo-600 text-white rounded-full shadow-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            @if (count($cart) > 0)
                <div
                    class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center">
                    {{ count($cart) }}
                </div>
            @endif
        </button>
    </div>

    <div class="fixed inset-0 overflow-hidden lg:hidden z-40" x-show="mobileCartOpen" x-cloak
        x-transition:enter="transition ease-in-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in-out duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div class="absolute inset-0 bg-gray-500 bg-opacity-75" @click="mobileCartOpen = false"></div>

        <div class="absolute inset-y-0 right-0 max-w-full flex" x-show="mobileCartOpen"
            x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

            <div class="w-screen max-w-md">
                <div class="h-full flex flex-col bg-white shadow-xl">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-neutral-200">
                        <h2 class="text-lg font-semibold text-neutral-900">Keranjang Belanja</h2>
                        <button @click="mobileCartOpen = false" class="text-neutral-500 hover:text-neutral-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto">
                        <div class="divide-y divide-neutral-200">
                            @forelse($cart as $item)
                                <div class="p-4">
                                    <div class="flex justify-between items-start gap-4">
                                        <div class="flex-shrink-0 w-16 h-16 bg-neutral-100 rounded-lg overflow-hidden">
                                            <img src="{{ asset('storage/' . $item['gambar']) }}"
                                                alt="{{ $item['nama'] }}" class="w-full h-full object-cover"
                                                loading="lazy">
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-medium text-neutral-900">{{ $item['nama'] }}</h4>
                                            <div class="text-sm text-neutral-500">{{ $item['kode'] }}</div>
                                            <div class="text-sm font-medium text-neutral-900">
                                                Rp
                                                {{ number_format($item['harga'], 0, ',', '.') }}/{{ $item['unit'] }}
                                            </div>
                                        </div>
                                        <button wire:click="removeFromCart({{ $item['id'] }})"
                                            class="text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="mt-2 flex items-center gap-4">
                                        <div class="flex items-center border border-neutral-200 rounded-md">
                                            <button
                                                wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})"
                                                class="p-1 text-neutral-500 hover:text-neutral-700">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M20 12H4" />
                                                </svg>
                                            </button>
                                            <input type="number" wire:model.live="cart.{{ $item['id'] }}.quantity"
                                                class="w-16 text-center border-0 p-0 focus:ring-0" min="1">
                                            <button
                                                wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})"
                                                class="p-1 text-neutral-500 hover:text-neutral-700">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="flex-1 text-right font-medium">
                                            Rp. {{ number_format($item['subtotal'], 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-neutral-500">
                                    Keranjang masih kosong
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="border-t border-neutral-200">
                        <div class="p-4">
                            <div class="flex justify-between items-center mb-4">
                                <div class="font-medium text-neutral-900">Total</div>
                                <div class="text-lg font-semibold text-neutral-900">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </div>
                            </div>

                            @if (count($cart) > 0)
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 mb-1">Metode
                                            Pembayaran</label>
                                        <div class="grid grid-cols-2 gap-2">
                                            <button wire:click="$set('paymentMethod', 'cash')"
                                                class="p-2 rounded-lg border-2 text-center {{ $paymentMethod === 'cash' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-neutral-200 text-neutral-600 hover:border-neutral-300' }}">
                                                <svg class="w-6 h-6 mx-auto mb-1" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                Tunai
                                            </button>
                                            <button wire:click="$set('paymentMethod', 'qris')"
                                                class="p-2 rounded-lg border-2 text-center {{ $paymentMethod === 'qris' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-neutral-200 text-neutral-600 hover:border-neutral-300' }}">
                                                <svg class="w-6 h-6 mx-auto mb-1" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                                </svg>
                                                QRIS
                                            </button>
                                        </div>
                                    </div>

                                    @if ($paymentMethod === 'cash')
                                        <div>
                                            <label class="block text-sm font-medium text-neutral-700 mb-1">Jumlah
                                                Bayar</label>
                                            <x-text-input wire:model.live="paymentAmount" type="number"
                                                class="w-full text-right" min="0" />
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <div class="font-medium text-neutral-900">Kembalian</div>
                                            <div
                                                class="text-lg font-semibold {{ $change > 0 ? 'text-green-600' : 'text-neutral-900' }}">
                                                Rp {{ number_format($change, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    @else
                                        <div
                                            class="text-center p-4 border-2 border-dashed border-neutral-300 rounded-lg">
                                            <div class="text-sm text-neutral-600 mb-2">Silahkan scan QR Code berikut
                                            </div>
                                            <img src="{{ asset('assets/qris.png') }}" alt="QRIS"
                                                class="mx-auto max-w-[200px]">
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-6 grid grid-cols-2 gap-3">
                                    <x-button wire:click="clearCart" variant="secondary" class="justify-center">
                                        Batal
                                    </x-button>
                                    <x-button wire:click="processPayment" class="justify-center" :disabled="count($cart) === 0 || ($paymentMethod === 'cash' && $paymentAmount < $total)">
                                        Bayar
                                    </x-button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="p-4 border-t border-neutral-200">
                        <x-button variant="secondary" @click="mobileCartOpen = false" class="w-full justify-center">
                            Tutup
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <x-modal name="success-modal" maxWidth="md">
        <div class="p-6">
            <div class="flex items-center justify-center mb-6">
                <div class="rounded-full bg-green-100 p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                </div>
            </div>

            <h2 class="text-lg font-medium text-center text-neutral-900 mb-2">
                Pembayaran Berhasil
            </h2>

            <p class="text-sm text-center text-neutral-600 mb-6">
                Transaksi telah berhasil diproses
            </p>

            <div class="flex flex-col gap-3">
                <a href="{{ $lastSaleId ? route('print.sale', $lastSaleId) : '#' }}" target="_blank">
                    <x-button class="w-full justify-center" variant="success">
                        <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                            </path>
                        </svg>
                        Cetak Struk
                    </x-button>
                </a>

                <x-button wire:click="closeSuccessModal" class="w-full justify-center" variant="secondary">
                    Tutup
                </x-button>
            </div>
        </div>
    </x-modal>
</div>
