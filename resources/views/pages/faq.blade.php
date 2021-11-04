@extends('templates.app')

@section('title', 'Frequently Asked Questions')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <faq></faq>
@endsection