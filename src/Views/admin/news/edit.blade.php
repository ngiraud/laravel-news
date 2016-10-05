@extends('layouts.admin')

@section('title', ' - '.trans('news::messages.news_title'))

@section('main')
	<h1 class="title-main">
		{{ trans('news::messages.title.edit', [ 'title' => $news->title ]) }}
		<div class="title_actions">
			<a href="{{ route('front.news.show', $news->slug) }}" target="_blank" class="tooltip-left" title="{{ trans('news::comments.title-add') }}">
				<span class="fa-stack">
				  	<i class="fa fa-circle fa-stack-2x"></i>
					<i class="fa fa-mail-forward fa-stack-1x"></i>
				</span>
			</a>
		</div>
	</h1>
	@include('news::admin.news._form')
@endsection

@section('submenu.news')
	@parent
	<li>
		<a href="{{ route('admin.news.edit', $news) }}" class="tooltip {{ (Route::currentRouteName() == 'admin.news.edit') ? 'active' : '' }}" {!! strlen($news->title) > 30 ? 'title="'.$news->title.'"' : '' !!}>
			<i class="fa fa-chevron-right"></i>{!! trans('news::messages.menu.title.edit', ['title' => Utils::getExcerpt($news->title, 30)]) !!}
		</a>
	</li>
@endsection

@push('additionnal-content')
	<div class="content-main">
		<h1 class="title-main">
			{!! trans_choice('news::comments.title', $news->all_comments_count, [ 'nb' => $news->all_comments_count ]) !!}
			<div class="title_actions">
				<a data-remodal-target="modal-comment-crud" class="tooltip-left" title="{{ trans('news::comments.title-add') }}">
				<span class="fa-stack">
				  	<i class="fa fa-circle fa-stack-2x"></i>
					<i class="fa fa-plus fa-stack-1x"></i>
				</span>
				</a>
			</div>
		</h1>
		@include('news::admin.comments.list', [ 'comments' => $comments, 'parent' => 0 ])
	</div>
	<div class="remodal remodal-custom" data-remodal-id="modal-alert">
		<button data-remodal-action="close" class="remodal-close"></button>
		<div class="remodal-content"></div>
		<div class="remodal-buttons">
			<button data-remodal-action="confirm" class="remodal-confirm">{{ trans('news::comments.btn.OK') }}</button>
		</div>
	</div>
	<div class="remodal remodal-custom" data-remodal-id="modal-comment-crud">
		<button data-remodal-action="close" class="remodal-close"></button>
		<div class="remodal-content">
			<h2></h2>
			<div class="errors"></div>
			<div class="search-input">
				<textarea id="comment_crud_content"></textarea>
			</div>
		</div>
		<div class="remodal-buttons">
			<button data-remodal-action="cancel" class="remodal-cancel">{{ trans('news::comments.btn.cancel') }}</button>
			<button data-remodal-action="confirm" class="remodal-confirm">{{ trans('news::comments.btn.add') }}</button>
		</div>
	</div>
@endpush

@push('scripts')
<script>
	(function($) {
		var modal_alert_js = $('[data-remodal-id="modal-alert"]');
		var modal_alert = modal_alert_js.remodal({ hashTracking: false, closeOnConfirm: false, closeOnOutsideClick: false, closeOnEscape: false });
		
		var modal_comment_crud_js = $('[data-remodal-id="modal-comment-crud"]');
		var modal_comment_crud = modal_comment_crud_js.remodal({ hashTracking: false, closeOnConfirm: false, closeOnOutsideClick: false, closeOnEscape: false });
		
		var $comment_crud_id = null;
		var $url_reply_comment = "{{ route('admin.comment.store', [ 'news_id' => $news->id, 'parent_id' => ':parent_comm' ]) }}";
		
		var $comments_counter = $('.comments_counter').text();
		
		$(document).on('opening', '.remodal[data-remodal-id="modal-comment-crud"]', function () {
			modal_comment_crud_js.find('.errors').empty();
			$('#comment_crud_content').val('');
			if($comment_crud_id == null) {
				$comment_crud_id = 0;
				modal_comment_crud_js.find('h2').html("{{ trans('news::comments.title-add') }}");
			} else {
				var $reply_author = $('#comment-'+$comment_crud_id+' > .one-comment .comment-author-name').text();
				$('#comment-'+$comment_crud_id+' > .one-comment').addClass('has-children');
				modal_comment_crud_js.find('h2').html("{{ trans('news::comments.title-reply') }} " + $reply_author);
			}
		});
		
		$(document).on('cancellation', '.remodal[data-remodal-id="modal-comment-crud"]', function () {
			if($comment_crud_id == null) {
				return false;
			}
			$('#comment-'+$comment_crud_id+' > .one-comment').removeClass('has-children');
			$comment_crud_id = null;
		});
		
		$(document).on('confirmation', '.remodal[data-remodal-id="modal-comment-crud"]', function () {
			modal_comment_crud_js.find('.errors').empty();
			var $url_store = $url_reply_comment.replace(':parent_comm', $comment_crud_id);
			$.ajax({
				url: $url_store,
				method: "POST",
				data: { comment_content: $('#comment_crud_content').val() },
				success: function(data){
					modal_alert_js.addClass('redirectAfterConfirm');
					modal_alert_js.find('.remodal-content').html(data.msg);
					modal_alert.open();
					
					if($comment_crud_id == 0) {
						$('.comments-list-parent').prepend(data.view);
					} else {
						if($('#comment-'+$comment_crud_id+' > ul').get(0)) {
							$('#comment-'+$comment_crud_id+' > ul').remove();
						}
						$('#comment-'+$comment_crud_id).append(data.view);
					}
					
					$comments_counter++;
					$('.comments_counter').html($comments_counter);
					
					$comment_crud_id = null;
				},
				error: function(data, json) {
					modal_comment_crud_js.find('.errors').html(data.responseJSON);
				},
			});
		});
		
		$(document).on('confirmation', '.remodal[data-remodal-id="modal-alert"]', function () {
			modal_alert.close();
		});
		
		$(document).on('click', '.comment-reply-link', function () {
			$comment_crud_id = $(this).attr('data-comment-id');
			modal_comment_crud.open();
			return false;
		});
		
		$('.content').on('click', '.comment-approve-link, .comment-disapprove-link', function() {
			var $thisLink = $(this);
			$.ajax({
				url: $(this).data('href'),
				method: "post",
				success: function(data){
					modal_alert_js.find('.remodal-content').html(data);
					$thisLink.parent().find('a').removeClass('hide');
					$thisLink.addClass('hide');
				},
				error: function(data, json) {
					modal_alert_js.find('.remodal-content').html(data.responseJSON);
				},
				complete: function(data) {
					modal_alert.open();
				}
			});
		});
	})(jQuery);
</script>
@endpush

{{--@push('additionnal-content')--}}
{{--<div class="content-main">--}}
	{{--<h1 class="title-main">--}}
		{{--{!! trans_choice('news::comments.title', $news->all_comments_count, [ 'nb' => $news->all_comments_count ]) !!}--}}
		{{--<div class="title_actions">--}}
			{{--<a data-remodal-target="modal-comment-crud" class="tooltip-left"--}}
			   {{--title="{{ trans('news::comments.title-add') }}">--}}
{{--<span class="fa-stack">--}}
{{--<i class="fa fa-circle fa-stack-2x"></i>--}}
{{--<i class="fa fa-plus fa-stack-1x"></i>--}}
{{--</span>--}}
			{{--</a>--}}
		{{--</div>--}}
	{{--</h1>--}}
	{{--<comments :news_id="{{ $news->id }}"></comments>--}}
{{--</div>--}}

{{--@push('scripts-plugins')--}}
{{--<script src="{{ asset('js/news.js') }}" type="text/javascript"></script>--}}
{{--@endpush--}}
{{--@endpush--}}