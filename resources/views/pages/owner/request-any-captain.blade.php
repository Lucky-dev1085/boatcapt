@extends('templates.app')

@section('title', 'Request a Captain')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
	@if($errors->any())
    <owner-request-any-captain v-bind:message="{{ json_encode(['status' => 'failed', 'body' => $errors->all()]) }}" v-bind:old-input="{{ json_encode(old()) }}"></owner-request-any-captain>
    @else
    <owner-request-any-captain></owner-request-any-captain>
    @endif
@endsection