@extends('layouts.inner')

@section('title', 'Админка')
@section('headline', 'Управление')

@section('content_inner')
    <p><a href="{{ route('admin.users.index') }}">Пользователи</a></p>
    <p><a href="{{ route('admin.news.index') }}">Новости</a></p>
@endsection
