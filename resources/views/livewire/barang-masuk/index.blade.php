<div class="p-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-900">Barang Masuk</h1>
            <p class="mt-1 text-sm text-neutral-600">Kelola transaksi pembelian barang dari supplier</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('stock-in.create') }}">
                <x-button>
                    <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Barang Masuk
                </x-button>
            </a>
        </div>
    </div>

    <div class="mb-6 p-4 bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-neutral-700 mb-1">Cari</label>
                <x-text-input wire:model.live.debounce.300ms="search" type="text" class="w-full"
                    placeholder="Cari nomor PO atau supplier..." />
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-neutral-700 mb-1">Supplier</label>
                <x-select wire:model.live="supplier" class="w-full">
                    <option value="">Semua Supplier</option>
                    @foreach ($suppliers as $s)
                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                    @endforeach
                </x-select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-neutral-700 mb-1">Rentang Tanggal</label>
                <x-text-input wire:model.live="dateRange" type="text" class="w-full"
                    placeholder="Pilih rentang tanggal" x-data x-init="flatpickr($el, {
                        mode: 'range',
                        dateFormat: 'Y-m-d',
                        locale: 'id'
                    })" />
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

    <div class="bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Nomor PO
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Supplier
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Petugas
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Total
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    @forelse($purchases as $purchase)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                                {{ $purchase->nomor }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ $purchase->tanggal->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ $purchase->supplier->nama }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ $purchase->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                Rp {{ number_format($purchase->total, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <a href="{{ route('barang-masuk.show', $purchase) }}"
                                    class="text-indigo-600 hover:text-indigo-900">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-neutral-500">
                                Tidak ada data barang masuk
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($purchases->hasPages())
            <div class="px-4 py-3 border-t border-neutral-200">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>
</div>
