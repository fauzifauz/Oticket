@props(['url'])
<tr>
<td class="header">
@php
    if (!isset($cms)) {
        $cms = \App\Models\CmsContent::pluck('value', 'key');
    }
@endphp
<a href="{{ $url }}" style="display: inline-block;">
@if(isset($cms['login_logo']) && $cms['login_logo'])
<img src="{{ asset('storage/' . $cms['login_logo']) }}" class="logo" alt="{{ config('app.name') }}" style="height: 50px; width: auto;">
@else
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo-v2.1.png" class="logo" alt="Laravel Logo">
@else
{!! $slot !!}
@endif
@endif
</a>
</td>
</tr>
