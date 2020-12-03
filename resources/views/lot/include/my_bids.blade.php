<div class="col-lg-12">
    <div class="ibox ">
        <div class="ibox-title">
            <h2>{{ $title }}</h2>
        </div>


        <div class="ibox-content">
            <div class="table-responsive">

                <table class="table table-striped table-bordered table-hover dataTables-example">
                    <thead>
                    <tr>
                        <td>ID лота</td>
                        <th style="width:60%">Номенклатура</th>
                        <th>Ставка НДС</th>
                        <th>Сумма с НДС</th>
                        <th>Ставка</th>

                    </tr>
                    </thead>
                    <tbody>

                    @php
                    // вывод ставки только указанного типа $type; если ставка пользователя -- максимальная для лота; если лот не закрыт
                    @endphp

                    @foreach($bids as $bid)
                        @if ($bid->lot->operation_type == $type &&
                             $bid->bid == $bid->maxBid($bid->lot_id)->max('bid') &&
                             !$bid->lot->accepted_bid_id)
                        <tr class="gradeA">
                            <td>{{ $bid->lot->id }}</td>
                            <td>{{ $bid->lot->nomenclature}}</td>
                            <td>{{ $bid->lot->NDS }} %</td>
                            <td>{{ number_format($bid->lot->sum, 2, ',', ' ') }} руб.</td>
                            <td>
                                {{ $bid->bid }}
                            </td>

                        </tr>
                        @endif
                    @endforeach

                    </tbody>
                </table>

            </div>
        </div>


    </div>
</div>
</div>

