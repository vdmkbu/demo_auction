@extends('layouts.inner')

@section('title', 'Предприятия')
@section('headline', 'Предприятия')

@section('content_inner')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <a href="{{ route('company.create') }}" class="btn btn-sm btn-success">Добавить предприятие</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <h2>Мои предприятия</h2>
    </div>


    @foreach($companies as $company)
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><a href="{{ route('company.show', $company->id) }}">{{ $company->name }}</a>
                    <small class="m-l-sm">ИНН: {{ $company->INN }}</small>
                    <small class="m-l-sm">Дата добавления: {{ date('d.m.Y H:s', strtotime($company->created_at)) }}</small>
                </h5>
            </div>
        </div>
    </div>
    @endforeach


</div>

@endsection
