@extends('layouts.admin')

@section('title', ' - '.trans('news::messages.news_title'))

@section('main')
	<h1 class="title-main">{{ trans('news::messages.title.add') }}</h1>
	@include('news::admin.news._form')
@endsection