@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    @if(auth()->user()->role == 'admin')
                    {{ __('Selamat datang admin ') }} {{ auth()->user()->name }}
                    @else
                    {{ __('Selamat datang user ') }} {{ auth()->user()->name }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection