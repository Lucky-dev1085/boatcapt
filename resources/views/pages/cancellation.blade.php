@extends('templates.app')

@section('title', 'Cancellation Policy')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <cancellation></cancellation>
@endsection