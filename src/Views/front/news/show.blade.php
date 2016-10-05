@extends('layouts.app')

@section('title', ' - '.$news->title)

@section('main')
	<div class="container news-container-unique page-container">
		<div class="row header">
			<h2>{{ $news->title }}</h2>
		</div>
		<div class="row">
			<div class="thumbnail news-unique">
				<div class="caption">
					<div class="post-meta small">
						{{ $news->published_at->diffInMonths(\Carbon\Carbon::now()) >= 1 ? $news->published_at->formatLocalized('%d/%m/%Y') : $news->published_at->diffForHumans() }}
					</div>
					<div class="content">{!! $news->content !!}</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="comment-container">
				@if(!$news->commentsApproved->isEmpty())
					<h3>{!! trans_choice('news::comments.title', $news->comments_approved_count, [ 'nb' => $news->comments_approved_count ]) !!}</h3>
					
					@if($news->comments_approved_count > 0)
						@include('news::front.comments.list', [ 'comments' => $comments, 'parent' => 0 ])
					@endif
				@endif
				<div class="comment-new-container">
					<div class="page-header comment-new-title">
						<h3 id="new-comment">{{ trans('front.comment.title-new') }}</h3>
					</div>
					
					<div class="row">
						<div class="col-xs-12 col-sm-8 col-md-6 col-lg-6">
							@include('partials.errors-admin')
							@include('partials.sessions-admin')
							@include('news::front.comments._form')
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection