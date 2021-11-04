@extends('templates.app')

@section('title', 'Request Any Captain Confirm')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
	@if($errors->any())
    <owner-book-any-captain-confirm v-bind:trip-info="{{ $tripInfo }}" v-bind:message="{{ json_encode(['status' => 'failed', 'body' => $errors->all()]) }}"></owner-book-any-captain-confirm>
    @else
    <owner-book-any-captain-confirm v-bind:trip-info="{{ $tripInfo }}"></owner-book-any-captain-confirm>
    @endif
@endsection