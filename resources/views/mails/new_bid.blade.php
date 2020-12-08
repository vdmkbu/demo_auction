@component('mail::message')
# Новая ставка на лот

ID лота: {{ $bid->lot_id }} <br>
ИНН предприятия: {{ $lot->company->INN }} <br>
Кто поставил ставку: {{ $bid->user->name }} <br>
Размер ставки, %: {{ $bid->bid }} <br>
Комиссия, руб.: {{ $commission }} <br>
Владелец лота: {{ $lot->user->name }} <br>



@endcomponent
