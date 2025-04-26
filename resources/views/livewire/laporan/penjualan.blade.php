<div>
    @include('components.alert')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-900">Laporan Penjualan</h1>
            <p class="mt-1 text-sm text-neutral-600">Rekap transaksi penjualan</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <x-button x-data="" x-on:click="$dispatch('open-modal', 'report-modal')" wire:click="$set('showModal', true)">
                <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Buat Laporan
            </x-button>
        </div>
    </div>

    <div class="mb-6 p-4 bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-neutral-700 mb-1">Cari</label>
                <x-text-input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    class="w-full"
                    placeholder="Cari laporan..." 
                />
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-neutral-700 mb-1">Rentang Tanggal</label>
                <x-text-input 
                    wire:model.live="dateRange" 
                    type="text" 
                    class="w-full"
                    placeholder="Pilih rentang tanggal" 
                    x-data
                    x-init="flatpickr($el, {
                        mode: 'range',
                        dateFormat: 'Y-m-d'
                    })"
                />
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg shadow-neutral-200/80 border border-neutral-200 overflow-hidden">
        <table class="min-w-full divide-y divide-neutral-200">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Periode</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase">Total Transaksi</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase">Total Penjualan</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase">Total Laba Kotor</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase">Dibuat Oleh</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
                @forelse($reports as $report)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            {{ $report->tanggal_awal->format('d/m/Y') }} - {{ $report->tanggal_akhir->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">{{ $report->total_transaksi }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                            Rp {{ number_format($report->total_penjualan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                            Rp {{ number_format($report->total_laba_kotor, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">{{ $report->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            <a href="{{ route('laporan.sales.show', $report) }}" 
                                class="text-indigo-600 hover:text-indigo-900">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-neutral-500">
                            Tidak ada data laporan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($reports->hasPages())
            <div class="px-4 py-3 border-t border-neutral-200">
                {{ $reports->links() }}
            </div>
        @endif
    </div>

    <!-- Report Modal -->
    <x-modal name="report-modal" :show="$showModal" maxWidth="md">
        <form wire:submit="generateReport" class="p-6">
            <h2 class="text-lg font-medium text-neutral-900">
                Buat Laporan Penjualan
            </h2>

            <div class="mt-6 space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="tanggal_awal" value="Tanggal Awal" />
                        <x-text-input wire:model="form.tanggal_awal" id="tanggal_awal" type="date" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('form.tanggal_awal')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tanggal_akhir" value="Tanggal Akhir" />
                        <x-text-input wire:model="form.tanggal_akhir" id="tanggal_akhir" type="date" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('form.tanggal_akhir')" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Batal
                </x-secondary-button>

                <x-button type="submit">
                    Buat Laporan
                </x-button>
            </div>
        </form>
    </x-modal>
</div>