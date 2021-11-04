@extends('templates.app')

@section('title', 'Privacy Policy')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <privacy></privacy>
@endsection