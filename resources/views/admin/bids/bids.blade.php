@extends('layouts.inner')

@section('title', 'Админка')
@section('headline', 'Все ставки для лота №' . $lot->id)

@section('content_inner')
    <div class="ibox-content">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID ставки</th>
                <th>Значение ставки</th>
                <th>Кто поставил</th>
                <th>Дата ставки</th>

            </tr>
            </thead>
            <tbody>
            @foreach($bids as $bid)
                <tr>
                    <td>{{ $bid->id }}</td>
                    <td>{{ $bid->bid }}</td>
                    <td>{{ $bid->user->name }}</td>
                    <td>{{ $bid->created_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
