<?php
if(!empty($news->id)) {
	$options = [ 'method' => 'put', 'url' => route('admin.news.update', $news), 'novalidate' => 'novalidate', 'files' => true ];
} else {
	$options = [ 'url' => route('admin.news.store'), 'novalidate' => 'novalidate', 'files' => true ];
}
?>
@include('partials.errors-admin')
@include('partials.sessions-admin')

{!! Form::model($news, $options) !!}
	<div class="form-group form-group-title">
		{!! Form::label('title', trans('validation.attributes.title')) !!}
		{!! Form::text('title', null, ['class' => 'form-control']) !!}
	</div>
	<div class="form-group checkbox right form-group-published">
		<label for="is_published">
			{{ trans('validation.attributes.is_published') }}
		</label>
		{!! Form::checkbox('is_published', 1, null, ['id' => 'is_published']) !!}
	</div>
	
	<div class="form-group form-group form-group-date">
		{!! Form::label('published_at', trans('validation.attributes.published_at')) !!}
		
		@php
		$published_at = old('published_at');
		if(is_null($published_at)) {
			$published_at = (empty($news->id)) ? \Carbon\Carbon::now()->toDateString() : $news->published_at->toDateString();
		}
		@endphp
		{!! Form::text('published_at', null, ['class' => 'form-control datepicker', 'id' => 'published_datepicker', 'data-value' => $published_at]) !!}
	</div>
	
	<div class="form-group form-group-file">
		{!! Form::label('url_image', trans('validation.attributes.url_image')) !!}
		{!! Form::file('url_image', [ 'class' => 'hidden' ]) !!}
		<button type="button" class="btn btn-classic">{{ trans('admin.btn.select') }}</button>
		<br />
		<img class="image_form" src="{{ empty($news) ? '' : $news->getUrlImage('thumb') }}" alt="{{ empty($news) ? '' : $news->url_image }}" />
	</div>

	<div class="form-group">
		{!! Form::label('content', trans('validation.attributes.content')) !!}
		{!! Form::textarea('content', null, ['class' => 'form-control', 'id' => 'content']) !!}
	</div>
	<div class="form-group form-btn">
		<button class="btn btn-save">{{ (!empty($news->id)) ? trans('admin.btn.update') : trans('admin.btn.create') }}</button>
		@if(!empty($news->id))
			<button type="button" id="remove_news" class="btn btn-remove">{{ trans('admin.btn.delete') }}</button>
		@endif
	</div>
{!! Form::close() !!}

<div class="remodal remodal-custom" data-remodal-id="modal-delete-news">
	<button data-remodal-action="close" class="remodal-close"></button>
	<div class="remodal-content">
		{{ trans('news::messages.deleted.confirm') }}
	</div>
	<div class="remodal-buttons">
		<button data-remodal-action="cancel" class="remodal-cancel">{{ trans('admin.btn.cancel') }}</button>
		<button data-remodal-action="confirm" class="remodal-confirm">{{ trans('admin.btn.OK') }}</button>
	</div>
</div>

<div class="remodal remodal-custom" data-remodal-id="modal-alert-news">
	<button data-remodal-action="close" class="remodal-close"></button>
	<div class="remodal-content"></div>
	<div class="remodal-buttons">
		<button data-remodal-action="confirm" class="remodal-confirm">{{ trans('admin.btn.OK') }}</button>
	</div>
</div>

@push('scripts-plugins')
	<script src="{!! asset('js/tinymce/tinymce.gzip.js') !!}" type="text/javascript"></script>
@endpush

@push('scripts')
	<script>
		(function($) {
			tinymce.init({
				selector: '#content',
				theme: 'modern',
				plugins: [
					'advlist autolink lists link image charmap print preview hr anchor pagebreak',
					'searchreplace wordcount visualblocks visualchars code fullscreen',
					'insertdatetime media nonbreaking table contextmenu directionality',
					'paste textcolor colorpicker textpattern imagetools autoresize'
				],
				language: 'fr_FR',
				menubar: false,
				toolbar1: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | forecolor backcolor | table',
				image_advtab: true,
				media_alt_source: false,
				media_poster: false,
				media_filter_html: false,
			});
			
			$('.form-group-file').find('input[type="file"]').on('change', function() {
				var reader = new FileReader();
				var $thisFileParent = $(this).parent();
				reader.onload = function (e) {
					$thisFileParent.find('img.image_form').attr('src', e.target.result);
				};
				reader.readAsDataURL(this.files[0]);
			});

			$('.form-group-file').find('button').on('click', function() {
				$(this).siblings('input[type="file"]').trigger('click');
			});

			$('.datepicker').pickadate({
				formatSubmit: 'yyyy-mm-dd',
				hiddenName: true,
			});

			@if(!empty($news->id))
				var modal = $('[data-remodal-id="modal-delete-news"]').remodal({ hashTracking: false, closeOnConfirm: false });
				var modal_alert_js = $('[data-remodal-id="modal-alert-news"]');
				var modal_alert = modal_alert_js.remodal({ hashTracking: false, closeOnConfirm: false });

				$('#remove_news').on('click', function() {
					modal.open();
				});

				$(document).on('confirmation', '.remodal[data-remodal-id="modal-delete-news"]', function () {
					$.ajax({
						url: '{{ route('admin.news.destroy', $news) }}',
						method: "DELETE",
						success: function(data){
							modal_alert_js.find('.remodal-content').html(data);
						},
						error: function(data, json) {
							modal_alert_js.find('.remodal-content').html(data.responseJSON);
						},
						complete: function(data) {
							modal_alert.open();
						}
					});
				});

				$(document).on('confirmation', '.remodal[data-remodal-id="modal-alert-news"]', function () {
					document.location.href = "{{ route('admin.news.index') }}";
				});
			@endif
		})(jQuery);
	</script>
@endpush