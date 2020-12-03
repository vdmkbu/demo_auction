<div class="row">

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
                            <td>ID</td>
                            <th style="width:50%">Номенклатура</th>
                            <th>Ставка НДС</th>
                            <th>Сумма с НДС</th>
                            <th>Хочу купить часть</th>
                            <th>Комиссия</th>

                        </tr>
                        </thead>
                        <tbody>

                        @foreach($lots as $lot)
                        <tr class="gradeA">
                            <td>{{ $lot->id }}</td>
                            <td>{{ $lot->nomenclature }} <button type="button" class="btn btn-default btn-xs"><a target="_blank" href="{{ route('lot.show', $lot) }}">ОКВЭД организации</a></button></td>
                            <td>{{ $lot->NDS }} %</td>
                            <td>{{ number_format($lot->sum, 2, ',', ' ') }} руб.</td>
                            <!--
                            масимальное значение не может быть больше f_Sum;
                            минимальное значение 50000 -
                            если f_Sum меньше, то устанавливается минимальным значением -- для лотов с небольшими суммами
                            -->
                            <td>
                                <input
                                    type="number"
                                    class="form-control input-sm set_part_bid"
                                    data-lot="{{ $lot->id }}"
                                    step="10000"
                                    max="{{ $lot->sum }}$f_Sum"
                                    min="{{ $lot->sum < 50000 ? $lot->sum : 50000 }}"
                                    value="{{ $lot->sum }}"> руб.
                            </td>
                            <td>

                                @if(isset($max_bids[$lot->id]['max_bid']))
                                    @if($max_bids[$lot->id]['max_bid'] >= $lot->fee)
                                        <i>{{ $max_bids[$lot->id]['max_bid'] }}</i> %
                                    @else
                                        {{ $lot->fee }} %
                                    @endif
                                @else
                                    {{ $lot->fee }} %
                                @endif

                                <button
                                    type="button"
                                    class="btn btn-primary btn-xs"
                                    data-toggle="modal"
                                    data-target="#myModal_{{ $lot->id }}">Сделать ставку
                                </button>
                            </td>

                        </tr>


                        <!--
                        $reserved = getReservedMoney($AUTH_USER_ID);
                        $free = $nc_core->user->get_by_id($AUTH_USER_ID, 'Account') - $reserved;
                        // если свободных средств меньше, чем комиссия --- вывод ошибки

                        // комиссия ставки в рублях
                        //$comission = ($f_Sum/100)*$next_fee;
                        # используется рассчет комиссии в /js/inspinia.js, т.к используется значение из поля .set_part_bid
                        -->
                        <!-- modal in modal -->
                        <div class="modal inmodal" id="myModal_{{ $lot->id }}" tabindex="-1" role="dialog" aria-hidden="true">

                            @if($free >= $comission = isset($max_bids[$lot->id]['next_fee']) ? ($lot->sum/100) * $max_bids[$lot->id]['next_fee'] : ($lot->sum/100) * $lot->fee)
                            <form class="ajax_form" data-id="{{ $lot->id }}" action="{{ route('bid.store', $lot) }}" method="POST">
                                <input type="hidden" name="Lot_ID" value="{{ $lot->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-content animated bounceInRight">

                                        <div class="modal-body">

                                            <div class="form-group"> <label>%</label>
                                                <div class="col-sm-5">
                                                    <input type="number" data-lot="{{ $lot->id }}"
                                                           data-sum="{{ $lot->sum }}"
                                                           onchange="this.value = parseFloat(this.value).toFixed(1);"
                                                           max="99"
                                                           min="{{ isset($max_bids[$lot->id]['next_fee']) ? $max_bids[$lot->id]['next_fee'] : $lot->fee}}"
                                                           step="0.1"
                                                           name="Bid"
                                                           value="{{ isset($max_bids[$lot->id]['next_fee']) ? $max_bids[$lot->id]['next_fee'] : $lot->fee}}"
                                                           class="form-control input-sm set_bid">
                                                </div>

                                                <div class="col-sm-6" style="float:right">
                                                    <strong>Размер комиссии: <span id="fee_value_{{ $lot->id }}"></span> р.</strong>
                                                </div>

                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            @if($free < $comission = isset($max_bids[$lot->id]['next_fee']) ? ($lot->sum/100) * $max_bids[$lot->id]['next_fee'] : ($lot->sum/100) * $lot->fee)

                                            <div class="col-sm-12">
                                                <div class="alert alert-danger text-center">На вашем балансе недостаточно средств</div>
                                            </div>
                                            @else
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Закрыть</button>
                                            <button id="set_bid_{{$lot->id}}" type="submit" class="btn btn-primary">Применить</button>
                                            @endif


                                        </div>
                                    </div>
                                </div>
                            </form>
                            @else
                            <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
                                    <div class="modal-body">
                                        <div class="alert alert-danger text-center">На вашем балансе недостаточно средств</div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-white" data-dismiss="modal">Закрыть</button>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>



                        @endforeach

                        </tbody>
                    </table>

                </div>
            </div>


        </div>
    </div>
</div>
