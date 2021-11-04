@extends('templates.app')

@section('title', 'Book Captain Confirm')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
	@if($errors->any())
    <owner-book-captain-confirm v-bind:captain-info="{{ $captainInfo }}" v-bind:trip-info="{{ $tripInfo }}" v-bind:is-admin="{{ isset($isAdmin)?$isAdmin:0 }}" v-bind:message="{{ json_encode(['status' => 'failed', 'body' => $errors->all()]) }}"></owner-book-captain-confirm>
    @else
    <owner-book-captain-confirm v-bind:captain-info="{{ $captainInfo }}" v-bind:trip-info="{{ $tripInfo }}" v-bind:is-admin="{{ isset($isAdmin)?$isAdmin:0 }}"></owner-book-captain-confirm>
    @endif
@endsection