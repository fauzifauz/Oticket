@php
    $logo = \App\Models\CmsContent::where('key', 'navbar_logo')->value('value');
    $logoUrl = $logo ? (str_starts_with($logo, 'http') ? $logo : asset('storage/' . $logo)) : null;
@endphp

@if($logoUrl)
    <img src="{{ $logoUrl }}" {{ $attributes->merge(['alt' => config('app.name'), 'class' => 'h-12 w-auto']) }}>
@else
    <!-- Fallback to a simple text logo or placeholder if no logo is found -->
    <span {{ $attributes->merge(['class' => 'font-bold text-2xl text-blue-600']) }}>
        {{ config('app.name', 'OTICKET') }}
    </span>
@endif
