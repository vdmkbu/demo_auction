@extends('layouts.inner')

@section('title', 'Лоты')
@section('headline', 'Редактировать лот')

@section('content_inner')
    <div class="ibox-content">
        <form enctype='multipart/form-data' class="form-horizontal" method='post' action='{{ route('lot.update', $lot) }}'>
            @csrf
            @method('PUT')
            <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">Выбрать предприятие</label>
                <div class="col-sm-10">
                    <select class="form-control m-b" name="company_id">

                        @foreach($companies as $company)
                            <option {{ $company->id == $lot->company_id ? 'selected' : ''}} value="{{ $company->id }}">{{ $company->name }}, <small>ИНН: {{ $company->INN }}</small></option>
                        @endforeach

                    </select>
                    @if ($errors->has('company_id'))
                        <span class="form-text m-b-none red-bg">{{ $errors->first('company_id') }}</span>
                    @endif
                </div> <!-- col-sm-10 -->
            </div>

            <div class="form-group{{ $errors->has('operation_type') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">Операция</label>
                <div class="col-sm-10">

                    <label class="checkbox-inline">
                        <div class="i-checks"><label>
                                <input type="radio" {{ $lot->operation_type == 1 ? 'checked="checked"' : '' }} value="1" name="operation_type"> <i></i> Покупка </label>
                        </div>
                    </label>
                    <label class="checkbox-inline">
                        <div class="i-checks"><label>
                                <input type="radio" {{ $lot->operation_type == 2 ? 'checked="checked"' : '' }} value="2" name="operation_type"> <i></i> Продажа </label>
                        </div>
                    </label>
                    @if ($errors->has('operation_type'))
                        <span class="form-text m-b-none red-bg">{{ $errors->first('operation_type') }}</span>
                    @endif
                </div> <!-- col-sm-10 -->
            </div>

            <div class="form-group{{ $errors->has('nomenclature') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">Номенклатура</label>
                <div class="col-sm-10">
                    <textarea maxlength="300" name="nomenclature" required="required" class="form-control">{{ old('nomenclature', $lot->nomenclature) }}</textarea>
                    @if ($errors->has('nomenclature'))
                        <span class="form-text m-b-none red-bg">{{ $errors->first('nomenclature') }}</span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('NDS') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">Ставка НДС <br></label>
                <div class="col-sm-10">

                    <label class="checkbox-inline">
                        <div class="i-checks-nds">
                            <label>
                                <input {{ $lot->NDS == 0 ? 'checked="checked"' : '' }} class="lot_nds" type="radio" data-value="0" value="0" name="NDS"> <i></i> Без НДС
                            </label>
                        </div>
                    </label>

                    <label class="checkbox-inline">
                        <div class="i-checks-nds">
                            <label>
                                <input {{ $lot->NDS == 10 ? 'checked="checked"' : '' }} class="lot_nds" type="radio" data-value="10" value="10" name="NDS"> <i></i> 10%
                            </label>
                        </div>
                    </label>

                    <label class="checkbox-inline">
                        <div class="i-checks-nds">
                            <label>
                                <input {{ $lot->NDS == 18 ? 'checked="checked"' : '' }} class="lot_nds" type="radio" data-value="18" value="18" name="NDS"> <i></i> 18%
                            </label>
                        </div>
                    </label>
                    @if ($errors->has('NDS'))
                        <span class="form-text m-b-none red-bg">{{ $errors->first('NDS') }}</span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('sum') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">Сумма с НДС</label>
                <div class="col-sm-10"><input type="number" max="99999999" required="required" value="{{ old('sum', $lot->sum) }}" name="sum" class="form-control lot_sum">
                    @if ($errors->has('sum'))
                        <span class="form-text m-b-none red-bg">{{ $errors->first('sum') }}</span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('fee') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">Коммиссия, % <br><small class="text-navy">от 2 до 99</small></label>
                <div class="col-sm-10"><input required="required" type="number" step="0.1" min="2" max="99" value="{{ old('fee', $lot->fee) }}" name="fee" class="form-control fee">
                    @if ($errors->has('fee'))
                        <span class="form-text m-b-none red-bg">{{ $errors->first('fee') }}</span>
                    @endif
                </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    <button class="btn btn-primary" type="submit">Обновить</button>
                </div>
            </div>
        </form>
    </div>
@endsection
