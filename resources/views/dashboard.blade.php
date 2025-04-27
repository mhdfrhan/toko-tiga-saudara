<x-app-layout>
    @php
        $hour = now()->format('H');
        $greeting = 'Selamat Pagi';
        if ($hour >= 12 && $hour < 18) {
            $greeting = 'Selamat Siang';
        } elseif ($hour >= 18 && $hour < 24) {
            $greeting = 'Selamat Malam';
        }

        $role = auth()->user()->role;
    @endphp

    <div class="py-12 bg-gray-50">
        <x-container>
            <section>
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-400 p-6 lg:p-8 rounded-2xl shadow-lg shadow-indigo-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl lg:text-3xl font-bold text-white">{{ $greeting }}, {{ auth()->user()->name }}</h1>
                            <p class="mt-2 text-lg text-indigo-100">Selamat datang di aplikasi {{ config('app.name') }}.</p>
                        </div>
                        <div class="hidden lg:block text-white/80">
                            <i class="fas fa-user-circle text-6xl"></i>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mt-10">
                <h2 class="text-xl lg:text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-bolt mr-3 text-amber-500"></i>
                    Akses Menu Cepat
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @if($role === 'kasir')
                        <a href="{{ route('kasir') }}" class="flex items-center p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow group">
                            <div class="p-3 bg-blue-50 rounded-lg group-hover:bg-blue-100 transition-colors">
                                <i class="fas fa-cash-register text-xl text-blue-500"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-medium text-gray-800">Kasir</h3>
                                <p class="text-sm text-gray-500">Transaksi Penjualan</p>
                            </div>
                        </a>
                    @endif

                    @if(in_array($role, ['admin', 'owner']))
                        <a href="{{ route('products') }}" class="flex items-center p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow group">
                            <div class="p-3 bg-green-50 rounded-lg group-hover:bg-green-100 transition-colors">
                                <i class="fas fa-box text-xl text-green-500"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-medium text-gray-800">Produk</h3>
                                <p class="text-sm text-gray-500">Kelola Produk</p>
                            </div>
                        </a>

                        <a href="{{ route('stock-in') }}" class="flex items-center p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow group">
                            <div class="p-3 bg-purple-50 rounded-lg group-hover:bg-purple-100 transition-colors">
                                <i class="fas fa-truck-loading text-xl text-purple-500"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-medium text-gray-800">Barang Masuk</h3>
                                <p class="text-sm text-gray-500">Stok Masuk</p>
                            </div>
                        </a>

                        <a href="{{ route('suppliers') }}" class="flex items-center p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow group">
                            <div class="p-3 bg-orange-50 rounded-lg group-hover:bg-orange-100 transition-colors">
                                <i class="fas fa-truck text-xl text-orange-500"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-medium text-gray-800">Supplier</h3>
                                <p class="text-sm text-gray-500">Kelola Supplier</p>
                            </div>
                        </a>
                    @endif

                    @if($role === 'owner')
                        <a href="{{ route('monitoring') }}" class="flex items-center p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow group">
                            <div class="p-3 bg-cyan-50 rounded-lg group-hover:bg-cyan-100 transition-colors">
                                <i class="fas fa-chart-line text-xl text-cyan-500"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-medium text-gray-800">Monitoring</h3>
                                <p class="text-sm text-gray-500">Analisis Bisnis</p>
                            </div>
                        </a>

                        <a href="{{ route('users') }}" class="flex items-center p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow group">
                            <div class="p-3 bg-rose-50 rounded-lg group-hover:bg-rose-100 transition-colors">
                                <i class="fas fa-users text-xl text-rose-500"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-medium text-gray-800">Pengguna</h3>
                                <p class="text-sm text-gray-500">Kelola Pengguna</p>
                            </div>
                        </a>
                    @endif
                </div>
            </section>

            <div class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <section class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-clock mr-3 text-indigo-500"></i>
                        Aktivitas Terbaru
                    </h2>
                    <div class="space-y-4">
                        @forelse($penjualans as $penjualan)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                                <div class="flex items-center">
                                    <div class="p-2 bg-indigo-50 rounded-lg">
                                        <i class="fas fa-shopping-bag text-sm text-indigo-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-800">Transaksi #{{ $penjualan->id }}</p>
                                        <p class="text-xs text-gray-500">{{ $penjualan->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                                <p class="text-sm font-medium text-gray-800">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</p>
                            </div>
                        @empty
                            <div class="text-center py-4 text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Belum ada aktivitas
                            </div>
                        @endforelse
                    </div>
                </section>

                <section class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-3 text-emerald-500"></i>
                        Informasi Sistem
                    </h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <div class="flex items-center">
                                <div class="p-2 bg-emerald-50 rounded-lg">
                                    <i class="fas fa-user text-sm text-emerald-500"></i>
                                </div>
                                <span class="ml-3 text-sm text-gray-600">Role</span>
                            </div>
                            <span class="text-sm font-medium text-gray-800 capitalize">{{ $role }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <div class="flex items-center">
                                <div class="p-2 bg-emerald-50 rounded-lg">
                                    <i class="fas fa-clock text-sm text-emerald-500"></i>
                                </div>
                                <span class="ml-3 text-sm text-gray-600">Waktu Server</span>
                            </div>
                            <span class="text-sm font-medium text-gray-800">{{ now()->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center">
                                <div class="p-2 bg-emerald-50 rounded-lg">
                                    <i class="fas fa-code-branch text-sm text-emerald-500"></i>
                                </div>
                                <span class="ml-3 text-sm text-gray-600">Versi Aplikasi</span>
                            </div>
                            <span class="text-sm font-medium text-gray-800">1.0.0</span>
                        </div>
                    </div>
                </section>
            </div>
        </x-container>
    </div>
</x-app-layout>