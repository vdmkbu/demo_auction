@extends('layouts.inner')

@section('title', 'Админка')
@section('headline', 'Добавление пользователя')

@section('content_inner')
    <div class="col-lg-12">
        <div class="ibox ">
            <div class="ibox-title">
                <h5>Добавление пользователя</h5>
            </div>
            <div class="ibox-content">
                <form enctype='multipart/form-data' method='post' action='{{ route('admin.users.store') }}'>
                    @csrf
                    <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}"><label class="col-sm-2 col-form-label">Имя</label>
                        <div class="col-sm-10">
                            <input type="text" name='name' required value="{{ old('name') }}" class="form-control">
                            @if ($errors->has('name'))
                                <span class="form-text m-b-none red-bg">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group row{{ $errors->has('email') ? ' has-error' : '' }}"><label class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text" name='email' required value="{{ old('email') }}" class="form-control">
                            @if ($errors->has('email'))
                                <span class="form-text m-b-none red-bg">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group row{{ $errors->has('password') ? ' has-error' : '' }}"><label class="col-sm-2 col-form-label">Пароль</label>
                        <div class="col-sm-10">
                            <input type="text" name='password' required value="{{ old('password') }}" class="form-control">
                            @if ($errors->has('password'))
                                <span class="form-text m-b-none red-bg">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group row"><label for="password-confirm" class="col-sm-2 col-form-label">Подтверждение пароля</label>

                        <div class="col-sm-10">
                            <input id="password-confirm" type="text" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group row{{ $errors->has('phone') ? ' has-error' : '' }}"><label class="col-sm-2 col-form-label">Телефон</label>
                        <div class="col-sm-10">
                            <input type="text" name='phone' value="{{ old('phone') }}" class="form-control">
                            @if ($errors->has('phone'))
                                <span class="form-text m-b-none red-bg">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group row{{ $errors->has('role') ? ' has-error' : '' }}"><label class="col-sm-2 col-form-label">Роль</label>
                        <div class="col-sm-10">
                            <select class="form-control m-b" name="role">

                                @foreach($roles as $role)
                                    <option value="{{ $role }}">{{ $role }}</option>
                                @endforeach

                            </select>
                            @if ($errors->has('role'))
                                <span class="form-text m-b-none red-bg">{{ $errors->first('role') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group row{{ $errors->has('account') ? ' has-error' : '' }}"><label class="col-sm-2 col-form-label">Счёт</label>
                        <div class="col-sm-10">
                            <input type="text" name='account' value="{{ old('account') }}" class="form-control">
                            @if ($errors->has('account'))
                                <span class="form-text m-b-none red-bg">{{ $errors->first('account') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button class="btn btn-primary btn-sm" type="submit">Добавить</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
