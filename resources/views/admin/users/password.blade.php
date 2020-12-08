@extends('layouts.inner')

@section('title', 'Админка')
@section('headline', 'Сменить пароль пользователя ' . $user->name)

@section('content_inner')
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-content">
                <form enctype='multipart/form-data' method='post' action='{{ route('admin.users.password.update', $user) }}'>
                    @csrf
                    @method('PUT')
                    <div class="form-group row{{ $errors->has('password') ? ' has-error' : '' }}"><label class="col-sm-2 col-form-label">Пароль</label>
                        <div class="col-sm-10">
                            <input type="text" name='password' required value="{{ old('password') }}" class="form-control">
                            @if ($errors->has('password'))
                                <span class="form-text m-b-none red-bg">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button class="btn btn-primary btn-sm" type="submit">Обновить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
