@extends('layouts.inner')

@section('title', 'Просмотр лота')
@section('headline', 'Лот №' . $lot->id)

@section('content_inner')
    <div class="row">
        <div class="col-lg-12">
            <h4>ОКВЭД</h4>
            <ul>
                @foreach(explode('|', $lot->company->OKVED) as $okved)
                <li>{{ $okved }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
