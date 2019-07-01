@extends('errors::illustrated-layout')

@section('title')
	{{ config('app.name', 'Laravel') }}
@endsection

<link rel="icon" href="{{ asset('img/cow-256.png') }}">

@section('code', '409')

@section('message', 'Conflicto')

@section('image')
    <div style="background-image: url({{ asset('/svg/403.svg') }});" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
    </div>
@endsection