@extends('layouts.inner')

@section('title', 'Мои лоты')
@section('headline', 'Мои лоты')

@section('content_inner')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <a href="{{ route('lot.create') }}" class="btn btn-sm btn-success">Добавить лот</a>
            </div>
        </div>
    </div>


        <div class="row">

            <div class="col-lg-12">
                <h2>Мои лоты</h2>
            </div>


            <div class="col-lg-12">

                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#tab-1">Покупка</a></li>
                        <li class=""><a data-toggle="tab" href="#tab-2">Продажа</a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane active">
                            @include('lot.include.my_lot_list', ['lots' => $lots->where('operation_type', \App\Models\Lot::TYPE_BUY)])
                        </div>

                        <div id="tab-2" class="tab-pane">
                            @include('lot.include.my_lot_list', ['lots' => $lots->where('operation_type', \App\Models\Lot::TYPE_SALE)])
                        </div>
                    </div>

                </div> <!-- tabs-container -->
            </div>
        </div>
@endsection
