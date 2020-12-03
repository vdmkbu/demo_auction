@if($lots->count())
<div class="panel-body">

    <div class="ibox-content">

        <table class="table table-bordered">
            <thead>
            <tr>
                <td>ID</td>
                <th>Предприятие</th>
                <th style="width:50%">Номенклатура</th>
                <th>Ставка НДС</th>
                <th>Сумма с НДС</th>
                <th>Комиссия</th>
                <th>Ставка</th>
                <th>Операции</th>
            </tr>
            </thead>
            <tbody>
            @foreach($lots as $lot)
            <tr>
                <td>{{ $lot->id }}</td>
                <td>{!! $lot->company->name !!}</td>
                <td>{{ $lot->nomenclature }}</td>
                <td>{{ $lot->NDS }} %</td>
                <td>{{ number_format($lot->sum, 2, ',', ' ') }} руб.</td>
                <td>{{ $lot->fee }} %</td>
                <td>
                    @if(isset($max_bids[$lot->id]))
                        {{ $max_bids[$lot->id] }} %
                    @endif


                    @if(isset($max_bids[$lot->id]) && !$lot->accepted_bid_id)
                        <button type="button" class="btn btn-success btn-xs close_lot" data-source="{{ route('lot.bid.accept', $lot->id) }}">Принять</button>
                    @elseif(isset($max_bids[$lot->id]) && $lot->accepted_bid_id)
                        <span class="badge badge-danger">Лот закрыт</span>
                    @endif

                </td>
                <td>

                    @if(!$lot->accepted_bid_id)
                    <div class="btn-group">
                        <button class="btn btn-default" onclick="window.location.href='{{ route('lot.edit', $lot) }}'" type="button" title="ред."><i class="fa fa-pencil" aria-hidden="true"></i></button>
                        <button class="btn btn-default delete_lot" data-source='{{ route('lot.delete', $lot) }}' type="button" title="удалить"><i class="fa fa-trash" aria-hidden="true"></i></button>
                    </div>
                    @endif

                </td>
            </tr>
            @endforeach

            </tbody>
        </table>

    </div>

</div>
@endif
