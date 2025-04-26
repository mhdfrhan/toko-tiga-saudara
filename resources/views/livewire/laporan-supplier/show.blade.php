<div>
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-900">Detail Laporan Supplier</h1>
            <p class="mt-1 text-sm text-neutral-600">
                Periode: {{ $report->tanggal_awal->format('d/m/Y') }} - {{ $report->tanggal_akhir->format('d/m/Y') }}
            </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('reports.suppliers') }}">
                <x-button variant="secondary">
                    <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </x-button>
            </a>
            <x-button variant="warning" wire:click="print">
                <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak
            </x-button>
        </div>
    </div>

    <!-- Supplier Info -->
    <div class="mb-6 bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-lg text-neutral-900 mb-4 font-semibold">Informasi Supplier</h2>
                <dl class="grid grid-cols-1 gap-3">
                    <div>
                        <dt class="text-sm font-medium text-neutral-500">Nama Supplier</dt>
                        <dd class="mt-1 text-base text-neutral-900">{{ $report->supplier->nama }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-neutral-500">Kontak</dt>
                        <dd class="mt-1 text-base text-neutral-900">{{ $report->supplier->kontak ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-neutral-500">Alamat</dt>
                        <dd class="mt-1 text-base text-neutral-900">{{ $report->supplier->alamat ?: '-' }}</dd>
                    </div>
                </dl>
            </div>
            <div>
                <h2 class="text-lg text-neutral-900 mb-4 font-semibold">Ringkasan Laporan</h2>
                <dl class="grid grid-cols-1 gap-3">
                    <div>
                        <dt class="text-sm font-medium text-neutral-500">Total Transaksi</dt>
                        <dd class="mt-1 text-base text-neutral-900">{{ $report->total_transaksi }} transaksi</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-neutral-500">Total Nominal</dt>
                        <dd class="mt-1 text-xl font-semibold text-neutral-900">
                            Rp {{ number_format($report->total_nominal, 0, ',', '.') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-neutral-500">Dibuat Oleh</dt>
                        <dd class="mt-1 text-base text-neutral-900">
                            {{ $report->user->name }}
                            <span class="text-sm text-neutral-500">
                                ({{ $report->created_at->format('d/m/Y H:i') }})
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-neutral-900">Daftar Transaksi</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">No
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Nomor PO</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Petugas</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    @forelse($details as $index => $barang)
                        <!-- Main Transaction Row -->
                        <tr class="hover:bg-neutral-50 cursor-pointer group">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-800">
                                {{ $barang->nomor }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ $barang->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ $barang->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-neutral-800">
                                Rp {{ number_format($barang->total, 0, ',', '.') }}
                            </td>
                        </tr>

                        <!-- Product Details Row -->
                        <tr class="bg-neutral-50/80">
                            <td colspan="5" class="px-4 py-2">
                                <div class="overflow-hidden rounded-lg border border-neutral-200 mb-2">
                                    <div class="px-4 py-2 bg-neutral-100 border-b border-neutral-200">
                                        <h3 class="text-sm font-medium text-neutral-600">Detail Produk</h3>
                                    </div>

                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-neutral-200">
                                            <thead class="bg-neutral-50">
                                                <tr>
                                                    <th
                                                        class="px-4 py-2 text-left text-xs font-medium text-neutral-500 uppercase">
                                                        Kode</th>
                                                    <th
                                                        class="px-4 py-2 text-left text-xs font-medium text-neutral-500 uppercase">
                                                        Nama Produk</th>
                                                    <th
                                                        class="px-4 py-2 text-right text-xs font-medium text-neutral-500 uppercase">
                                                        Harga</th>
                                                    <th
                                                        class="px-4 py-2 text-right text-xs font-medium text-neutral-500 uppercase">
                                                        Jumlah</th>
                                                    <th
                                                        class="px-4 py-2 text-right text-xs font-medium text-neutral-500 uppercase">
                                                        Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-neutral-200">
                                                @foreach ($barang->detail as $detail)
                                                    <tr>
                                                        <td
                                                            class="px-4 py-2 whitespace-nowrap text-sm text-neutral-500">
                                                            {{ $detail->produk->kode_barang }}
                                                        </td>
                                                        <td class="px-4 py-2 text-sm text-neutral-600">
                                                            {{ $detail->produk->nama }}
                                                        </td>
                                                        <td
                                                            class="px-4 py-2 whitespace-nowrap text-sm text-right text-neutral-600">
                                                            Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                                        </td>
                                                        <td
                                                            class="px-4 py-2 whitespace-nowrap text-sm text-right text-neutral-600">
                                                            {{ $detail->jumlah }}
                                                        </td>
                                                        <td
                                                            class="px-4 py-2 whitespace-nowrap text-sm text-right font-medium text-neutral-700">
                                                            Rp
                                                            {{ number_format($detail->harga * $detail->jumlah, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr class="bg-neutral-50">
                                                    <td colspan="4"
                                                        class="px-4 py-2 text-sm font-medium text-right text-neutral-700">
                                                        Total</td>
                                                    <td
                                                        class="px-4 py-2 text-sm font-medium text-right text-neutral-800">
                                                        Rp {{ number_format($barang->total, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-sm text-center text-neutral-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-neutral-300 mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                        </path>
                                    </svg>
                                    <p class="font-medium">Tidak ada data transaksi</p>
                                    <p class="text-neutral-400 text-xs mt-1">Belum ada transaksi yang tercatat dalam
                                        sistem</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($details->hasPages())
            <div class="px-6 py-3 border-t border-neutral-200">
                {{ $details->links() }}
            </div>
        @endif
    </div>
</div>
