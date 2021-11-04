@extends('templates.app')

@section('title', 'Send Request Any Captain')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
	<owner-send-request-any v-bind:captain-list="{{ $captainList }}"></owner-send-request-any>
@endsection