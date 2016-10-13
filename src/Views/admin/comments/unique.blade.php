<li class="one-comment-object" id="comment-{{ $comment->id }}">
	<div class="one-comment {{ !empty($comments[$comment->id]) && !$comments[$comment->id]->isEmpty() ? 'has-children' : '' }}">
		<img class="comment-avatar" src="{{ $comment->user_id == 0 ? asset('img/avatar.png') : $comment->user->getAvatar() }}" />
		<div class="comment-body">
			<div class="comment-author-name">{{ $comment->author_name }}</div>
			<div class="comment-meta">{{ $comment->updated_at->diffForHumans() }}</div>
			<div class="comment-content">{{ $comment->content }}</div>
			<div class="comment-reply">
				<a class="btn btn-red comment-reply-link" data-comment-id="{{ $comment->id }}">
					<i class="fa fa-pencil"></i> {{ trans('news::comments.btn.reply') }}
				</a>
				<a class="btn btn-green comment-approve-link {{ ($comment->approved_status == 1) ? 'hide' : '' }}" data-href="{{ route('admin.comment.approve', [$news, $comment]) }}">
					<i class="fa fa-thumbs-up"></i> {{ trans('news::comments.btn.approve') }}
				</a>
				<a class="btn btn-remove comment-disapprove-link {{ ($comment->approved_status == 0) ? 'hide' : '' }}" data-href="{{ route('admin.comment.disapprove', [$news, $comment]) }}">
					<i class="fa fa-thumbs-down"></i> {{ trans('news::comments.btn.disapprove') }}
				</a>
			</div>
		</div>
	</div>
	@if(!empty($comments[$comment->id]) && !$comments[$comment->id]->isEmpty())
		@include('news::admin.comments.list', [ 'comments' => $comments, 'parent' => $comment->id ])
	@endif
</li>