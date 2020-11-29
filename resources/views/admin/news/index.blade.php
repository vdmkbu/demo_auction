@extends('layouts.inner')

@section('content_inner')
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <a href="{{ route('admin.news.create') }}" class="btn btn-sm btn-success">Добавить новость</a>
        </div>
    </div>
    <div class="ibox-content">
    <table class="table table-bordered">
        <thead>
        <tr>
            <td>ID</td>
            <th style="width:50%">Заголовок</th>
            <th>Управление</th>

        </tr>
        </thead>
        <tbody>

        @foreach($news as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }} <small>{{ date('d.m.Y H:i', strtotime($item->date)) }}</small></td>
                <td>
                    <button type="button" class="btn btn-success btn-xs">
                        <a style="color: white" href="{{ route('admin.news.edit', $item->id) }}">Редактировать</a>
                    </button>
                    <form method="POST" action="{{ route('admin.news.destroy', $item->id) }}" style="display: inline-block">
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
