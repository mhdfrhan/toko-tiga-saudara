@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'style' => 'filled',
])

<button type="{{ $type }}"
    {{ $attributes->merge([
        'class' =>
            'inline-flex items-center justify-center rounded-lg transition-all duration-200 font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-95 cursor-pointer ' .
            match ($size) {
                'sm' => 'px-3 py-1.5 text-sm',
                'lg' => 'px-6 py-3 text-lg',
                default => 'px-4 py-2.5 text-sm',
            } .
            ' ' .
            match ($variant) {
                'primary' => $style === 'filled'
                    ? 'bg-indigo-500 text-white hover:bg-indigo-600 focus:ring-indigo-500 shadow-lg shadow-indigo-500/30'
                    : 'text-indigo-500 border-2 border-indigo-500 hover:bg-indigo-50 focus:ring-indigo-500',
                'secondary' => $style === 'filled'
                    ? 'bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-gray-400 shadow-lg shadow-gray-200/30'
                    : 'text-gray-700 border-2 border-gray-200 hover:bg-gray-50 focus:ring-gray-400',
                'danger' => $style === 'filled'
                    ? 'bg-red-500 text-white hover:bg-red-600 focus:ring-red-500 shadow-lg shadow-red-500/30'
                    : 'text-red-500 border-2 border-red-500 hover:bg-red-50 focus:ring-red-500',
                'success' => $style === 'filled'
                    ? 'bg-green-500 text-white hover:bg-green-600 focus:ring-green-500 shadow-lg shadow-green-500/30'
                    : 'text-green-500 border-2 border-green-500 hover:bg-green-50 focus:ring-green-500',
                'info' => $style === 'filled'
                    ? 'bg-blue-500 text-white hover:bg-blue-600 focus:ring-blue-500 shadow-lg shadow-blue-500/30'
                    : 'text-blue-500 border-2 border-blue-500 hover:bg-blue-50 focus:ring-blue-500',
                'warning' => $style === 'filled'
                    ? 'bg-orange-500 text-white hover:bg-orange-600 focus:ring-orange-500 shadow-lg shadow-orange-500/30'
                    : 'text-orange-500 border-2 border-orange-500 hover:bg-orange-50 focus:ring-orange-500',
                default => 'bg-indigo-500 text-white hover:bg-indigo-600 focus:ring-indigo-500',
            },
    ]) }}>
    {{ $slot }}
</button>
