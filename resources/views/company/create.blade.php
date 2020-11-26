@extends('layouts.inner')

@section('title', 'Предприятия')
@section('headline', $method == 'put' ? 'Редактировать предприятие' : 'Добавить предприятие')

@section('content_inner')
<form enctype='multipart/form-data' method='post' action='{{ $method == 'post' ? route('company.store') : route('company.update', $company)}}'>
    @csrf
    @if ($method == 'put')
        @method('PUT')
    @endif
    <p><input type="file" name="file"/></p>
    @error('file')
    <p>
        <span class="text-danger" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    </p>
    @enderror
    <button class="btn btn-primary" type="submit">{{ $method == 'put' ? 'Обновить' : 'Добавить' }}</button>
    <span class="help-block m-b-none">Файл выписки в формате PDF (не более 3 МБ)</span>
    <div class="text-danger">
        <strong>Файл выписки можно скачать на сайте <a class="alert-link" target="_blank" href="https://egrul.nalog.ru">https://egrul.nalog.ru</a></strong>
    </div>

</form>
@endsection
