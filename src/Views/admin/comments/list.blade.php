<ul class="comments-list {{ $parent == 0 ? 'comments-list-parent' : 'comments-children' }}">
	@if(!empty($comments[$parent]) && !$comments[$parent]->isEmpty())
		@foreach($comments[$parent] as $comment)
			@include('news::admin.comments.unique', [ 'comments' => $comments, 'comment' => $comment ])
		@endforeach
	@endif
</ul>