<x-app-layout>
    @php
        // buat ucapan sesuai dengan waktu
        $hour = now()->format('H');
        $greeting = 'Selamat Pagi';
        if ($hour >= 12 && $hour < 18) {
            $greeting = 'Selamat Siang';
        } elseif ($hour >= 18 && $hour < 24) {
            $greeting = 'Selamat Malam';
        }
    @endphp
    <div class="py-12">
        <x-container>
            <section>
                <div
                    class="bg-gradient-to-br from-indigo-500 to-indigo-400 p-6 lg:p-8 rounded-xl shadow-2xl shadow-indigo-200/80">
                    <h1 class="text-2xl lg:text-3xl font-bold text-white">{{ $greeting }}, {{ auth()->user()->name }}
                    </h1>
                    <p class="mt-2 text-lg text-indigo-200">Selamat datang di aplikasi {{ config('app.name') }}.</p>
                </div>
            </section>

            <section class="mt-14">
                <h2 class="text-xl lg:text-2xl font-semibold">Akses Menu Cepat</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                    <a href="" class="bg-white shadow-lg rounded-lg shadow-neutral-200 p-5 hover:bg-indigo-500 duration-300"></a>
                </div>
            </section>
        </x-container>
    </div>
</x-app-layout>
