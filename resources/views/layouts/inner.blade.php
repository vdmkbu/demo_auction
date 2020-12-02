@extends('layouts.app')

<!-- меню слева -->
@section('content')
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span></span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">

                                    <span class="block m-t-xs"><strong class="font-bold">Login</strong> <b class="caret"></b></span>
                                 </span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="/profile">Профиль</a></li>

                        <li class="divider"></li>
                        <li><a href="">Выход</a></li>
                    </ul>


                </div>

                <span class="text-info">Баланс: Account р. </span>
                <span class="text-info">Свободные: ['Account']-$reserved р.</span>
                <span class="text-info">Резерв: $reserved р.</span>
                <div class="logo-element">
                    b-con
                </div>
            </li>

            <!-- меню -->
            <li class="navbar-default"><a href="{{ route('home') }}"><i class="fa fa-newspaper-o"></i> <span class="nav-label">Новости</span></a></li>
            <li class="navbar-default"><a href="{{ route('company.index') }}"><i class="fa fa-building-o"></i> <span class="nav-label">Предприятия</span></a></li>
            <li class="navbar-default"><a href="{{ route('lot.index') }}"><i class="fa fa-building-o"></i> <span class="nav-label">Мои лоты</span></a></li>

            @can('admin_panel')
            <li class="navbar-default"><a href="{{ route('admin.index') }}"><i class="fa fa-list"></i> <span class="nav-label">Admin</span></a></li>
            @endcan

        </ul>

    </div>
</nav>

<div id="page-wrapper" class="gray-bg dashbard-1">

    <!-- хедер -->
    <div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                <form role="search" class="navbar-form-custom" action="search_results.html">
                    <div class="form-group">
                        <input type="text" placeholder="Поиск по сайту..." class="form-control" name="top-search" id="top-search">
                    </div>
                </form>
            </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message">Добро пожаловать</span>
                </li>

                <li>
                    <a href="">
                        <i class="fa fa-sign-out"></i> Выход
                    </a>
                </li>
                <li>
                    <a class="right-sidebar-toggle">
                        <i class="fa fa-tasks"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div> <!-- row border-bottom -->

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@yield('headline')</h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    @yield('content_inner')
</div>

</div>
@endsection
