@extends('layouts.inner')

@section('title', 'Админка')
@section('headline', 'Пользователи')

@section('content_inner')
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-success">Добавить пользователя</a>
        </div>
    </div>

    <div class="ibox-content">
        <table class="table table-bordered">
            <thead>
            <tr>
                <td>ID</td>
                <th>Имя</th>
                <th>Email</th>
                <th>Телефон</th>
                <th>Счёт</th>
                <th>Роль</th>
                <th>Управление</th>

            </tr>
            </thead>
            <tbody>

            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->account }}</td>
                    <td>{{ $user->role }}</td>
                    <td>
                        <button type="button" class="btn btn-warning btn-xs">
                            <a style="color: white" href="{{ route('admin.users.password', $user->id) }}">Сменить пароль</a>
                        </button>

                        <button type="button" class="btn btn-success btn-xs">
                            <a style="color: white" href="{{ route('admin.users.edit', $user->id) }}">Редактировать</a>
                        </button>
                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" style="display: inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-xs">
                                Удалить
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
