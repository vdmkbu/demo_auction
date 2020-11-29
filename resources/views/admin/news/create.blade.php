@extends('layouts.inner')

@section('title', 'Админка')
@section('headline', 'Добавление новости')

@section('content_inner')
    <div class="col-lg-12">
        <div class="ibox ">
            <div class="ibox-title">
                <h5>Добавление новости</h5>
            </div>
            <div class="ibox-content">
                <form enctype='multipart/form-data' method='post' action='{{ route('admin.news.store') }}'>
                    @csrf
                    <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}"><label class="col-sm-2 col-form-label">Заголовок</label>
                        <div class="col-sm-10">
                            <input type="text" name='name' required value="{{ old('name') }}" class="form-control">
                            @if ($errors->has('name'))
                                <span class="form-text m-b-none red-bg">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group row{{ $errors->has('date') ? ' has-error' : '' }}"><label class="col-sm-2 col-form-label">Дата</label>
                        <div class="col-sm-10">
                            <input type="text" name='date' value="{{ date('Y-m-d H:i', strtotime(now())) }}" required class="form-control">
                            @if ($errors->has('date'))
                            <span class="form-text m-b-none red-bg">{{ $errors->first('date') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group row{{ $errors->has('announce') ? ' has-error' : '' }}"><label class="col-sm-2 col-form-label">Анонс новости</label>
                        <div class="col-sm-10">
                            <textarea name='announce' class="form-control">{{ old('announce') }}</textarea>
                            @if ($errors->has('announce'))
                                <span class="form-text m-b-none red-bg">{{ $errors->first('announce') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group row{{ $errors->has('text') ? ' has-error' : '' }}"><label class="col-sm-2 col-form-label">Текст новости</label>
                        <div class="col-sm-10">
                            <textarea name='text' id="text" required class="form-control">{{ old('text') }}</textarea>
                            @if ($errors->has('text'))
                                <span class="form-text m-b-none red-bg">{{ $errors->first('text') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group row{{ $errors->has('files') ? ' has-error' : '' }}"><label class="col-sm-2 col-form-label">Галерея изображений</label>
                        <div class="col-sm-10">
                            <input type="file" name="files[]" multiple />
                            @if ($errors->has('files'))
                                <span class="form-text m-b-none red-bg">{{ $errors->first('files') }}</span>
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
