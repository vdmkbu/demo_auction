@extends('layouts.inner')

@section('title', 'Предприятия - ' . $company->name)
@section('headline', $company->name)

@section('content_inner')
    <div class="row">


        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>{{ $company->name }}
                        <small class="m-l-sm">ИНН: {{ $company->INN }}</small>
                        <small class="m-l-sm">Дата добавления: {{ date('d.m.Y H:i', strtotime($company->created_at)) }}</small>
                    </h5>
                    <span class="pull-right">
                            <button type="button" class="btn btn-default btn-xs"><a target="_blank" href="{{ $download_link }}">скачать выписку</a> </button>
                            <button type="button" class="btn btn-default btn-xs"><a href=" {{ route('company.edit', $company->id) }}">обновить выписку</a> </button>



                        @if(!$company->lots()->exists())
                            <form method="POST" action="{{ route('company.destroy', $company->id) }}" style="display: inline-block">
                            @csrf
                                @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-xs">
                                удалить предприятие
                            </button>
                        </form>
                        @endif

                        </span>
                </div>
                <div class="ibox-content">
                    <h4>ОКВЭД</h4>
                    @foreach(explode('|',$company->OKVED) as $okved_item)
                    <p> {{ $okved_item }}</p>
                    @endforeach
                </div>

                <div class="ibox-footer">

                    дата выписки: {{ date('d.m.Y', strtotime($company->date)) }}
                </div>

            </div>
        </div>


    </div>
@endsection
