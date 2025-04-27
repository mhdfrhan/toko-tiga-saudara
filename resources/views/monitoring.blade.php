<x-app-layout>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @endpush

    <div class="py-12 bg-gray-50">
        <x-container>
            <div class="mb-6 p-6 bg-white rounded-2xl shadow-sm">
                <form action="{{ route('monitoring') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-600 mb-2">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <span>Periode</span>
                        </label>
                        <x-select name="period" class="rounded-xl w-full border-gray-200"
                            onchange="this.form.submit()">
                            <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Harian</option>
                            <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                            <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                            <option value="quarterly" {{ $period === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                            <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Tahunan</option>
                        </x-select>
                    </div>

                    @if (in_array($period, ['daily', 'weekly']))
                        <div class="md:col-span-2 grid grid-cols-2 gap-4">
                            <div>
                                <label class="flex items-center text-sm font-medium text-gray-600 mb-2">
                                    <i class="fas fa-calendar-day mr-2"></i>
                                    <span>Tanggal Mulai</span>
                                </label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}"
                                    class="rounded-xl w-full border-gray-200" onchange="this.form.submit()">
                            </div>
                            <div>
                                <label class="flex items-center text-sm font-medium text-gray-600 mb-2">
                                    <i class="fas fa-calendar-day mr-2"></i>
                                    <span>Tanggal Akhir</span>
                                </label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}"
                                    class="rounded-xl w-full border-gray-200" onchange="this.form.submit()">
                            </div>
                        </div>
                    @else
                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-600 mb-2">
                                <i class="fas fa-calendar-check mr-2"></i>
                                <span>Tahun</span>
                            </label>
                            <x-select name="year" class="rounded-xl w-full border-gray-200"
                                onchange="this.form.submit()">
                                @for ($i = now()->year; $i >= 2020; $i--)
                                    <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                        {{ $i }}</option>
                                @endfor
                            </x-select>
                        </div>
                    @endif
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-50 to-white p-6 rounded-2xl shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-medium text-gray-600">Total Penjualan</h3>
                        <i class="fas fa-shopping-cart text-blue-500"></i>
                    </div>
                    <p class="text-2xl font-semibold text-gray-800">Rp
                        {{ number_format($summary['total_sales'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-white p-6 rounded-2xl shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-medium text-gray-600">Total Laba</h3>
                        <i class="fas fa-chart-line text-green-500"></i>
                    </div>
                    <p class="text-2xl font-semibold text-gray-800">Rp
                        {{ number_format($summary['total_profit'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-white p-6 rounded-2xl shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-medium text-gray-600">Margin Laba</h3>
                        <i class="fas fa-percentage text-purple-500"></i>
                    </div>
                    <p class="text-2xl font-semibold text-gray-800">
                        {{ number_format($summary['average_profit_margin'], 1) }}%</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-800">Trend Penjualan & Laba</h2>
                        <i class="fas fa-chart-area text-gray-400"></i>
                    </div>
                    <canvas id="salesAndProfitChart" class="max-h-[300px]"></canvas>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-800">Top 10 Produk</h2>
                        <i class="fas fa-trophy text-gray-400"></i>
                    </div>
                    <canvas id="topProductsChart" class="max-h-[300px]"></canvas>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-800">Analisis Supplier</h2>
                        <i class="fas fa-truck text-gray-400"></i>
                    </div>
                    <canvas id="supplierChart" class="max-h-[300px]"></canvas>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-800">Top 5 Kategori</h2>
                        <i class="fas fa-tags text-gray-400"></i>
                    </div>
                    <canvas id="topCategoriesChart" class="max-h-[300px]"></canvas>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-800">Peringatan Stok</h2>
                    <i class="fas fa-exclamation-triangle text-gray-400"></i>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Produk</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Stok Saat Ini</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Minimal Stok</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Selisih</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($lowStockProducts as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->nama }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $product->stok }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $product->min_stok }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $product->stock_diff }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($product->stock_diff < 0)
                                            <span
                                                class="px-3 py-1 text-xs font-medium rounded-full bg-red-50 text-red-600">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                Stok Kritis
                                            </span>
                                        @else
                                            <span
                                                class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-50 text-yellow-600">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Perlu Restock
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Tidak ada produk dengan stok menipis
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-container>
    </div>

    @push('scripts')
        <script>
            const chartColors = {
                blue: 'rgba(79, 70, 229, 0.2)',
                green: 'rgba(16, 185, 129, 0.2)',
                red: 'rgba(239, 68, 68, 0.2)',
                yellow: 'rgba(245, 158, 11, 0.2)',
                purple: 'rgba(139, 92, 246, 0.2)'
            };

            const borderColors = {
                blue: 'rgba(79, 70, 229, 1)',
                green: 'rgba(16, 185, 129, 1)',
                red: 'rgba(239, 68, 68, 1)',
                yellow: 'rgba(245, 158, 11, 1)',
                purple: 'rgba(139, 92, 246, 1)'
            };

            // Sales and Profit Trend Chart
            new Chart(document.getElementById('salesAndProfitChart'), {
                type: 'line',
                data: {
                    labels: @json($salesAndProfitTrend->pluck('period')),
                    datasets: [{
                        label: 'Penjualan',
                        data: @json($salesAndProfitTrend->pluck('total_sales')),
                        borderColor: borderColors.blue,
                        backgroundColor: chartColors.blue,
                        tension: 0.1
                    }, {
                        label: 'Laba',
                        data: @json($salesAndProfitTrend->pluck('total_profit')),
                        borderColor: borderColors.green,
                        backgroundColor: chartColors.green,
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => 'Rp ' + new Intl.NumberFormat('id-ID').format(value)
                            }
                        }
                    }
                }
            });

            // Top Products Chart
            new Chart(document.getElementById('topProductsChart'), {
                type: 'bar',
                data: {
                    labels: @json($topProducts->pluck('nama')),
                    datasets: [{
                        label: 'Laba',
                        data: @json($topProducts->pluck('total_profit')),
                        backgroundColor: chartColors.green,
                        borderColor: borderColors.green,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => 'Rp ' + new Intl.NumberFormat('id-ID').format(value)
                            }
                        }
                    }
                }
            });

            // Supplier Analysis Chart
            new Chart(document.getElementById('supplierChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($supplierAnalysis->pluck('nama')),
                    datasets: [{
                        data: @json($supplierAnalysis->pluck('total_amount')),
                        backgroundColor: [
                            chartColors.blue,
                            chartColors.green,
                            chartColors.red,
                            chartColors.yellow,
                        ],
                        borderColor: [
                            borderColors.blue,
                            borderColors.green,
                            borderColors.red,
                            borderColors.yellow,
                        ],
                        borderWidth: 1
                    }]
                }
            });

            // Top Categories Chart
            new Chart(document.getElementById('topCategoriesChart'), {
                type: 'bar',
                data: {
                    labels: @json($topCategories->pluck('nama')),
                    datasets: [{
                        label: 'Jumlah Item Terjual',
                        data: @json($topCategories->pluck('total_items')),
                        backgroundColor: chartColors.red,
                        borderColor: borderColors.red,
                        borderWidth: 1,
                        order: 1
                    }, {
                        label: 'Total Penjualan',
                        data: @json($topCategories->pluck('total_sales')),
                        backgroundColor: chartColors.blue,
                        borderColor: borderColors.blue,
                        borderWidth: 1,
                        yAxisID: 'y1',
                        order: 2
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Jumlah Item'
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Total Penjualan (Rp)'
                            },
                            grid: {
                                drawOnChartArea: false
                            },
                            ticks: {
                                callback: value => 'Rp ' + new Intl.NumberFormat('id-ID').format(value)
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: '5 Kategori Terbanyak Dibeli'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    let value = context.parsed.y;

                                    if (context.datasetIndex === 1) { // Total Penjualan
                                        return label + ': Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                    }
                                    return label + ': ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
