<div>
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-900">Detail Laporan Penjualan</h1>
            <p class="mt-1 text-sm text-neutral-600">
                Periode: {{ $report->tanggal_awal->format('d/m/Y') }} - {{ $report->tanggal_akhir->format('d/m/Y') }}
            </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('reports.sales') }}">
                <x-button variant="secondary">
                    <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </x-button>
            </a>
            <x-button wire:click="print">
                <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Laporan
            </x-button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-5">
                    <h3 class="text-sm font-medium text-neutral-500">Total Transaksi</h3>
                    <p class="mt-1 text-xl font-semibold text-neutral-900">{{ $report->total_transaksi }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-5">
                    <h3 class="text-sm font-medium text-neutral-500">Total Penjualan</h3>
                    <p class="mt-1 text-xl font-semibold text-neutral-900">Rp {{ number_format($report->total_penjualan, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-5">
                    <h3 class="text-sm font-medium text-neutral-500">Total Laba Kotor</h3>
                    <p class="mt-1 text-xl font-semibold text-neutral-900">Rp {{ number_format($report->total_laba_kotor, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-200">
            <h2 class="text-lg font-semibold text-neutral-900">Daftar Transaksi</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">No Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Kasir</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase">Laba Kotor</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    @forelse($details as $penjualan)
                        <tr class="hover:bg-neutral-50 cursor-pointer" x-data="{ open: false }">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 font-semibold">
                            {{ $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                                {{ $penjualan->nomor }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ $penjualan->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ $penjualan->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium">
                                Rp {{ number_format($penjualan->total, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-green-600">
                                Rp {{ number_format($penjualan->laba_kotor, 0, ',', '.') }}
                            </td>
                        </tr>
                        <!-- Detail Row -->
                        <tr x-show="open" x-cloak class="bg-neutral-50/80">
                            <td colspan="6" class="px-4 py-2">
                                <div class="overflow-hidden rounded-lg border border-neutral-200 mb-2">
                                    <div class="px-4 py-2 bg-neutral-100 border-b border-neutral-200">
                                        <h3 class="text-sm font-medium text-neutral-600">Detail Produk</h3>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-neutral-200">
                                            <thead class="bg-neutral-50">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-neutral-500">Produk</th>
                                                    <th class="px-4 py-2 text-right text-xs font-medium text-neutral-500">Harga Beli</th>
                                                    <th class="px-4 py-2 text-right text-xs font-medium text-neutral-500">Harga Jual</th>
                                                    <th class="px-4 py-2 text-right text-xs font-medium text-neutral-500">Qty</th>
                                                    <th class="px-4 py-2 text-right text-xs font-medium text-neutral-500">Subtotal</th>
                                                    <th class="px-4 py-2 text-right text-xs font-medium text-neutral-500">Laba</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-neutral-200">
                                                @foreach($penjualan->detail as $detail)
                                                    <tr>
                                                        <td class="px-4 py-2 text-sm text-neutral-600">
                                                            {{ $detail->produk->nama }}
                                                        </td>
                                                        <td class="px-4 py-2 text-sm text-right text-neutral-600">
                                                            Rp {{ number_format($detail->produk->harga_beli, 0, ',', '.') }}
                                                        </td>
                                                        <td class="px-4 py-2 text-sm text-right text-neutral-600">
                                                            Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                                        </td>
                                                        <td class="px-4 py-2 text-sm text-right text-neutral-600">
                                                            {{ $detail->jumlah }}
                                                        </td>
                                                        <td class="px-4 py-2 text-sm text-right text-neutral-600">
                                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                                        </td>
                                                        <td class="px-4 py-2 text-sm text-right font-medium text-green-600">
                                                            Rp {{ number_format(($detail->harga - $detail->produk->harga_beli) * $detail->jumlah, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-neutral-500">
                                Tidak ada data transaksi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($details->hasPages())
            <div class="px-6 py-3 border-t border-neutral-200">
                {{ $details->links() }}
            </div>
        @endif
    </div>
</div>