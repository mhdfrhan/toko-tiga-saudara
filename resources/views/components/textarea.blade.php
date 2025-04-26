@props(['rows' => 3])

<textarea {{ $attributes->merge(['class' => 'mt-1 block w-full border-neutral-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500']) }} rows="{{ $rows }}">
	 {{ $slot }}
</textarea>
