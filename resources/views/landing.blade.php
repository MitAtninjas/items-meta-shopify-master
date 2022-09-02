@extends('layouts.front')

@section('content')
    <!-- Hero -->
    <div class="hero bg-white overflow-hidden">
        <div class="hero-inner">
            <div class="content content-full text-center">
                <h1 class="font-w700 mb-2">
                    Active<span class="text-primary">Ants</span>
                </h1>
                <h2 class="h4 font-w400 text-muted mb-4 invisible" data-toggle="appear" data-timeout="150">
                    Welcome to the {{ config('app.name', 'Shopify App') }}!
                </h2>
                <span class="m-2 d-inline-block invisible" data-toggle="appear" data-timeout="300">
                    @if (Route::has('login'))
                        <div class="top-right links">
                            @auth
                                @if(auth()->user()->hasRole('admin'))
                                    <a class="btn btn-primary" href="{{ route('admin.dashboard') }}">
                                        <i class="fa fa-fw fa-tachometer-alt mr-1"></i> Dashboard
                                    </a>
                                @else
                                    <a class="btn btn-primary" href="{{ route('sales.dashboard') }}">
                                        <i class="fa fa-fw fa-tachometer-alt mr-1"></i> Dashboard
                                    </a>
                                @endif
                            @else
                                <a class="btn btn-primary" href="{{ route('login') }}">
                                    <i class="fa fa-fw fa-sign-in-alt mr-1"></i> Login
                                </a>

                                @if (Route::has('register'))
                                <a class="btn btn-secondary" href="{{ route('register') }}">
                                    <i class="fa fa-fw fa-user-check mr-1"></i> Register
                                </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </span>
            </div>
        </div>
    </div>
    <!-- END Hero -->
@endsection
