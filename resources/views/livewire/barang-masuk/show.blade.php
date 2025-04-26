<div class="p-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-900">Detail Barang Masuk</h1>
            <p class="mt-1 text-sm text-neutral-600">
                Nomor PO: {{ $barangMasuk->nomor }}
            </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('stock-in') }}">
                <x-button variant="secondary">
                    Kembali
                </x-button>
            </a>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200 p-6">
            <div class="text-sm font-medium text-neutral-600">Supplier</div>
            <div class="mt-2 text-2xl font-semibold text-neutral-900">
                {{ $barangMasuk->supplier->nama }}
            </div>
            <div class="mt-1 text-sm text-neutral-500">
                {{ $barangMasuk->supplier->kontak ?: 'Tidak ada kontak' }}
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200 p-6">
            <div class="text-sm font-medium text-neutral-600">Tanggal</div>
            <div class="mt-2 text-2xl font-semibold text-neutral-900">
                {{ $barangMasuk->created_at->format('d/m/Y') }}
            </div>
            <div class="mt-1 text-sm text-neutral-500">
                {{ $barangMasuk->created_at->format('H:i') }}
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200 p-6">
            <div class="text-sm font-medium text-neutral-600">Petugas</div>
            <div class="mt-2 text-xl font-semibold text-neutral-900">
                {{ $barangMasuk->user->name }}
            </div>
            <div class="mt-1 text-sm text-neutral-500">
                {{ $barangMasuk->user->role }}
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200 p-6">
            <div class="text-sm font-medium text-neutral-600">Total</div>
            <div class="mt-2 text-2xl font-semibold text-neutral-900">
                Rp {{ number_format($barangMasuk->total, 0, ',', '.') }}
            </div>
            <div class="mt-1 text-sm text-neutral-500">
                {{ $barangMasuk->detail->count() }} items
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Kode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Produk
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Harga
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Jumlah
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Subtotal
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    @foreach ($barangMasuk->detail as $detail)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                {{ $detail->produk->kode_barang }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="font-medium text-neutral-900">{{ $detail->produk->nama }}</div>
                                <div class="text-neutral-500">{{ $detail->produk->unit->nama }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                Rp {{ number_format($detail->harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                {{ $detail->jumlah }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-neutral-50">
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                            Total
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                            Rp {{ number_format($barangMasuk->total, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @if ($barangMasuk->catatan)
        <div class="mt-6 bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200 p-6">
            <h3 class="text-lg font-medium text-neutral-900 mb-2">Catatan</h3>
            <p class="text-neutral-600">{{ $barangMasuk->catatan }}</p>
        </div>
    @endif
</div>
