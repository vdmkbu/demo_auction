@extends('layouts.app')

@section('content')

    <div class="loginColumns animated fadeInDown">
        <div class="row">
            <div class="col-md-6">
                <h2 class="font-bold">Добро пожаловать на БИЗНЕС-КОННЕКТ</h2>
                <p>Соединяем интересы</p>
            </div>
            <div class="col-md-6">
                <div class="ibox-content">
                    <form class="m-t" role="form" method="POST" action="{{ route('login') }}">
                    @csrf
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Логин" name="email" value="{{ old('email') }}" required="">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" placeholder="Пароль" name="password" required="">
                        </div>
                        <div class="form-group">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Запомнить логин и пароль
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary block full-width m-b">Войти</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
