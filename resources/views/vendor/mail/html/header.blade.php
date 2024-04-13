@props(['url'])
<tr>
<td class="header">
<a href="htps://app.thesvnetwork.com" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://api.thesvnetwork.com/storage/images/logo.png" class="logo" alt="Svnetwork Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
