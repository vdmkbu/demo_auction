@extends('layouts.inner')

@section('title', 'Новости')
@section('headline', 'Новости')

@section('content_inner')
<div class="fh-breadcrumb">

    <div class="fh-column">
        <div class="full-height-scroll">
            <ul class="list-group elements-list">
                @foreach($posts as $index => $post)
                <li class="list-group-item{{ $index == 0 ? ' active':'' }}">
                    <a data-toggle="tab" href="#tab-{{ $post->id }}">

                        <strong>{{ $post->name }}</strong>
                        <div class="small m-t-xs">
                            <p>{{ $post->announce }}</p>
                            <p class="m-b-none">
                                <small class="pull-right text-muted">{{ date('d.m.Y', strtotime($post->date)) }}</small>
                            </p>
                        </div>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- КОНТЕНТ НОВОСТИ -->
    <div class="full-height">
        <div class="full-height-scroll white-bg border-left">

            <div class="element-detail-box">

                <div class="tab-content">

                    @foreach($posts as $index => $post)
                    <div id="tab-{{ $post->id }}" class="tab-pane{{ $index == 0 ? ' active':'' }}">


                        <div class="small text-muted">
                            <i class="fa fa-clock-o"></i> {{ date('d.m.Y H:i', strtotime($post->date)) }}
                        </div>

                        <h1>{{ $post->name }}</h1>

                        {!! $post->text !!}

                        @if ($post->images->count())
                        <div class="m-t-lg">

                            <div class="lightBoxGallery">

                                @foreach($post->images as $image)
                                <a href="{{ \Illuminate\Support\Facades\Storage::url($image->path) }}" title="" data-gallery="">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($image->path) }}">
                                </a>
                                @endforeach

                                <div id="blueimp-gallery" class="blueimp-gallery">
                                    <div class="slides"></div>
                                    <h3 class="title"></h3>
                                    <a class="prev">‹</a>
                                    <a class="next">›</a>
                                    <a class="close">×</a>
                                    <a class="play-pause"></a>
                                    <ol class="indicator"></ol>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                        </div>
                        @endif
                        <!-- m-t-lg -->


                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


</div>
@endsection
