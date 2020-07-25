@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <a href="{{ route('login.provider', 'google') }}" 
                            class="btn btn-secondary">{{ __('Google Sign in') }}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
