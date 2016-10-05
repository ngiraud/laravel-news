{!! Form::model(new \NGiraud\News\Models\Comment(), [ 'url' => route('front.comment.store', [ 'news_id' => $news->id, 'parent_id' => 0 ]) ]) !!}
	<div class="form-group">
		{!! Form::label('author_name', trans('validation.attributes.author_name'), ['class' => 'is-required']) !!}
		{!! Form::text('author_name', null, ['class' => 'form-control', 'placeholder' => trans('front.form.placeholder.your-name')]) !!}
	</div>
	<div class="form-group">
		{!! Form::label('author_email', trans('validation.attributes.author_email'), ['class' => 'is-required']) !!}
		{!! Form::text('author_email', null, ['class' => 'form-control', 'placeholder' => trans('front.form.placeholder.your-email')]) !!}
	</div>
	<div class="form-group">
		{!! Form::label('content', trans('validation.attributes.comment_content'), ['class' => 'is-required']) !!}
		{!! Form::textarea('comment_content', null, ['class' => 'form-control']) !!}
	</div>
	<div class="form-group form-btn">
		<button class="btn btn-save">{{ trans('messages.btn.add-comment') }}</button>
	</div>
{!! Form::close() !!}