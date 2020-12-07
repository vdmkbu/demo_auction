@component('mail::message')
# Новая ставка на лот

<table border="1">
@foreach($data as $label => $value)
        <tr><td>{{ $label }}: {{ $value }}</td></tr>
@endforeach
</table>


@endcomponent
