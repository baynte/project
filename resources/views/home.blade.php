@extends('layouts.app')

@section('content')
    <home-component :auth="{{auth()->user()}}" csrf="{{  csrf_token() }}"></home-component>
@endsection
