@extends('layouts.inner')

@section('title', 'Админка')
@section('headline', 'Редактирование пользователя')

@section('content_inner')
    <div class="col-lg-12">
        <div class="ibox ">
            <div class="ibox-title">
                <h5>Редактирование пользователя</h5>
            </div>
            <div class="ibox-content">
                <form enctype='multipart/form-data' class="form-horizontal" method='post' action='{{ route('admin.users.update', $user) }}'>
                    @csrf
                    @method('PUT')
                    <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}"><label class="col-sm-2 col-form-label">Имя</label>
                        <div class="col-sm-10">
                            <input type="text" name='name' required value="{{ old('name', $user->name) }}" class="form-control">
                            @if ($errors->has('name'))
                                <span class="form-text m-b-none red-bg">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group row{{ $errors->has('email') ? ' has-error' : '' }}"><label class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text" name='email' required value="{{ old('email', $user->email) }}" class="form-control">
                            @if ($errors->has('email'))
                                <span class="form-text m-b-none red-bg">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group row{{ $errors->has('phone') ? ' has-error' : '' }}"><label class="col-sm-2 col-form-label">Телефон</label>
                        <div class="col-sm-10">
                            <input type="text" name='phone' value="{{ old('phone', $user->phone) }}" class="form-control">
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
                                    <option {{ $user->role == $role ? 'selected' : '' }} value="{{ $role }}">{{ $role }}</option>
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
                            <input type="text" name='account' value="{{ old('account', $user->account) }}" class="form-control">
                            @if ($errors->has('account'))
                                <span class="form-text m-b-none red-bg">{{ $errors->first('account') }}</span>
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
