<select {{ $attributes->merge(['class' => 'mt-1 block w-full border-neutral-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500']) }}>
    {{ $slot }}
</select>
