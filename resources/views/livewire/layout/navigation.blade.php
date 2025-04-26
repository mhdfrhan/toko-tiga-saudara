<?php
use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }

    public function getMenusByRole()
    {
        $role = auth()->user()->role;
        $menus = [];

        // Base menus for all roles
        $menus['dashboard'] = [
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>',
            'name' => 'Dashboard',
            'route' => 'dashboard',
        ];

        if ($role === 'owner') {
            $menus['monitoring'] = [
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>',
                'name' => 'Monitoring',
                'route' => 'monitoring',
            ];
            $menus['users'] = [
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>',
                'name' => 'Kelola User',
                'route' => 'users',
            ];
        }

        if ($role === 'admin' || $role === 'owner') {
            $menus['suppliers'] = [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                </svg>',
                'name' => 'Suppliers',
                'hasDropdown' => true,
                'dropdownItems' => [
                    [
                        'name' => 'Data Supplier',
                        'route' => 'suppliers',
                    ],
                    [
                        'name' => 'Barang Masuk',
                        'route' => 'stock-in',
                    ],
                ],
            ];
            $menus['products'] = [
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>',
                'name' => 'Produk',
                'hasDropdown' => true,
                'dropdownItems' => [
                    [
                        'name' => 'Kategori',
                        'route' => 'products.categories',
                    ],
                    [
                        'name' => 'Kelola Produk',
                        'route' => 'products',
                    ],
                ],
            ];
            $menus['reports'] = [
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                'name' => 'Laporan',
                'hasDropdown' => true,
                'dropdownItems' => [
                    [
                        'name' => 'Supplier',
                        'route' => 'reports.suppliers',
                    ],
                    [
                        'name' => 'Penjualan',
                        'route' => 'reports.sales',
                    ],
                ],
            ];
        }

        if ($role === 'kasir') {
            $menus['kasir'] = [
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>',
                'name' => 'Kasir',
                'route' => 'kasir',
            ];
        }

        return $menus;
    }
}; ?>

<nav x-data="{ open: false, dropdown: false }" class="bg-white border-b border-neutral-100 sticky w-full top-0 z-50">
    <!-- Primary Navigation Menu -->
    <x-container>
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="shrink-0 flex items-center">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('assets/logo.png') }}" alt="{{ config('app.name') }} Logo" class="h-10 w-auto" />
                </a>
            </div>

            <div class="hidden space-x-5 lg:ml-10 lg:flex items-center">
                @foreach ($this->getMenusByRole() as $menu)
                    @if (isset($menu['hasDropdown']) && $menu['hasDropdown'])
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center {{ request()->routeIs($menu['dropdownItems'][0]['route']) || request()->routeIs($menu['dropdownItems'][1]['route']) ? 'border-b-2 border-indigo-400 text-neutral-900' : 'border-b-2 border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300' }}">
                                    <x-nav-link class="inline-flex items-center h-16 hover:border-transparent">
                                        {!! $menu['icon'] !!}
                                        <span class="ml-2">{{ $menu['name'] }}</span>
                                    </x-nav-link>
                                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                @foreach ($menu['dropdownItems'] as $item)
                                    <x-dropdown-link :href="route($item['route'])"
                                        class="{{ request()->routeIs($item['route']) ? 'bg-neutral-100' : '' }}">
                                        {{ $item['name'] }}
                                    </x-dropdown-link>
                                @endforeach
                            </x-slot>
                        </x-dropdown>
                    @else
                        <x-nav-link :href="route($menu['route'])" :active="request()->routeIs($menu['route'])" class="inline-flex items-center h-16">
                            {!! $menu['icon'] !!}
                            <span class="ml-2">{{ $menu['name'] }}</span>
                        </x-nav-link>
                    @endif
                @endforeach
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden lg:flex lg:items-center lg:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 bg-white p-2 rounded-lg hover:bg-neutral-50 transition-all">
                            <div class="h-8 w-8 rounded-full bg-neutral-200 flex items-center justify-center text-neutral-700 font-semibold text-sm">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="hidden md:block text-left">
                                <div class="text-sm font-semibold text-neutral-800">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-neutral-500">{{ ucfirst(auth()->user()->role) }}</div>
                            </div>
                            <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>
            
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')">
                            Profile
                        </x-dropdown-link>
                        
                        <button wire:click="logout" class="w-full text-left">
                            <x-dropdown-link>
                                Log Out
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center lg:hidden">
                <button @click="open = !open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-neutral-400 hover:text-neutral-500 hover:bg-neutral-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </x-container>

    <!-- Mobile menu -->
    <!-- For mobile navigation -->
    <div :class="{ 'block': open, 'hidden': !open }" class="lg:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @foreach ($this->getMenusByRole() as $menu)
                @if (isset($menu['hasDropdown']) && $menu['hasDropdown'])
                    <div x-data="{ dropdownOpen: false }">
                        <button @click="dropdownOpen = !dropdownOpen"
                            class="flex w-full items-center px-3 py-2 text-base font-medium {{ request()->routeIs($menu['dropdownItems'][0]['route']) || request()->routeIs($menu['dropdownItems'][1]['route']) ? 'bg-neutral-50 text-neutral-900' : 'text-neutral-600 hover:text-neutral-800 hover:bg-neutral-50' }}">
                            {!! $menu['icon'] !!}
                            <span class="ml-2">{{ $menu['name'] }}</span>
                            <svg class="ml-auto w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="dropdownOpen" class="pl-6 pr-2">
                            @foreach ($menu['dropdownItems'] as $item)
                                <x-responsive-nav-link :href="route($item['route'])" :active="request()->routeIs($item['route'])"
                                    class="{{ request()->routeIs($item['route']) ? 'bg-neutral-50 text-neutral-900' : 'text-neutral-600 hover:bg-neutral-50' }}">
                                    {{ $item['name'] }}
                                </x-responsive-nav-link>
                            @endforeach
                        </div>
                    </div>
                @else
                    <x-responsive-nav-link :href="route($menu['route'])" :active="request()->routeIs($menu['route'])" class="flex items-center">
                        {!! $menu['icon'] !!}
                        <span class="ml-2">{{ $menu['name'] }}</span>
                    </x-responsive-nav-link>
                @endif
            @endforeach
        </div>

        <div class="pt-4 pb-1 border-t border-neutral-200">
            <div class="px-4">
                <div class="font-medium text-base text-neutral-800">{{ auth()->user()->name }}</div>
                <div class="font-medium text-sm text-neutral-500">{{ ucfirst(auth()->user()->role) }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')">
                    Profile
                </x-responsive-nav-link>
                <button wire:click="logout" class="w-full text-left">
                    <x-responsive-nav-link>
                        Log Out
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
