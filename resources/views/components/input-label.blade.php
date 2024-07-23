@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-white-700']) }}>
    {{ $value ?? $slot }}
</label>
