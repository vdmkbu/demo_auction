@extends('layouts.inner')

@section('title', 'Все продажи')
@section('headline', 'Все продажи')

@section('content_inner')
    <div class="col-lg-12">

        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#tab-1">Все</a></li>
                <li class=""><a data-toggle="tab" href="#tab-2">Мои ставки</a></li>
            </ul>

            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    @include('lot.include.lot_list', ['title' => 'Продажа', 'lots' => $lots])
                </div>

                <div id="tab-2" class="tab-pane">
                    @include('lot.include.my_bids', ['title' => 'Продажа', 'type' => \App\Models\Lot::TYPE_SALE])
                </div>
            </div>

        </div> <!-- tabs-container -->

    </div>
@endsection
