@extends('layouts.inner')

@section('title', 'Админка')
@section('headline', 'Все лоты со ставками')

@section('content_inner')
    <div class="ibox-content">
        <table class="table table-bordered">
            <thead>
            <tr>
                <td>ID лота</td>
                <th style="width:50%">Номенклатура</th>
                <th>Управление</th>

            </tr>
            </thead>
            <tbody>
            @foreach($lots as $lot)
            <tr>
                <td>{{ $lot->id }}</td>
                <td>{{ $lot->nomenclature }}</td>
                <td>
                    <a href="{{ route('admin.bids.show', $lot) }}">Посмотреть все ставки лота</a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
