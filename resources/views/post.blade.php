@extends('Layout/layout')
@section('content')
    <input type="hidden" id="authId" value="{{auth()->check()?auth()->id():0}}">
    <div id="post"></div>
@endsection
