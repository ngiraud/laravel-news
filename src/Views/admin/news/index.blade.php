@extends('layouts.admin')

@section('title', ' - '.trans('news::messages.news_title'))

@section('main')
	<h1 class="title-main">
		{{ trans('news::messages.title.list') }}
		<div class="title_actions">
			<a href="{{ route('admin.news.create') }}" class="tooltip-left" title="{{ trans('news::messages.menu.title.add') }}">
				<span class="fa-stack">
				  	<i class="fa fa-circle fa-stack-2x"></i>
					<i class="fa fa-plus fa-stack-1x"></i>
				</span>
			</a>
		</div>
	</h1>

	<table id="news_list" class="table-news stripe hover compact">
		<thead>
		<tr>
			@if(Auth::user()->isSuperAdmin())
				<th style="width: 30px;">{{ trans('admin.table.columns.ID') }}</th>
			@endif
			<th style="width: 30%;">{{ trans('news::messages.table.title') }}</th>
			<th>{{ trans('news::messages.table.excerpt') }}</th>
			<th style="width: 50px;">{{ trans('news::messages.table.published') }}</th>
			<th></th>
			<th style="width: 150px;">{{ trans('news::messages.table.published_date') }}</th>
			<th style="width: 50px;"></th>
		</tr>
		</thead>
		<tbody>
			@foreach($news as $the_new)
				<tr>
					@if(Auth::user()->isSuperAdmin())
						<td>{{ $the_new->id }}</td>
					@endif
					<td>{{ $the_new->title }}</td>
					<td>{!! Utils::excerpt_content($the_new->content, 10) !!}</td>
					<td>{{ ($the_new->is_published == 1) ? trans('admin.yes') : trans('admin.no') }}</td>
					<td>{{ ($the_new->is_published == 1) ? $the_new->published_at->formatLocalized('%d/%m/%Y %H:%M:%S') : '' }}</td>
					<td>{{ ($the_new->is_published == 1) ? $the_new->published_at->formatLocalized('%d/%m/%Y %H:%M') : '' }}</td>
					<td class="table_actions">
						<a href="{{ route('admin.news.edit', $the_new) }}" class="tooltip-left" title="{{ trans('news::messages.edit.tooltip') }}">
							<i class="fa fa-pencil"></i>
						</a>
						<a class="tooltip-left remove_news" title="{{ trans('news::messages.deleted.tooltip') }}"
						   data-url = "{{ route('admin.news.destroy', $the_new) }}">
							<i class="fa fa-remove"></i>
						</a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

	<div class="remodal remodal-custom" data-remodal-id="modal-delete-news" data-remodal-options="hashTracking: false">
		<button data-remodal-action="close" class="remodal-close"></button>
		<div class="remodal-content">
			{{ trans('news::messages.deleted.confirm') }}
		</div>
		<div class="remodal-buttons">
			<button data-remodal-action="cancel" class="remodal-cancel">{{ trans('admin.btn.cancel') }}</button>
			<button data-remodal-action="confirm" class="remodal-confirm">{{ trans('admin.btn.OK') }}</button>
		</div>
	</div>

	<div class="remodal remodal-custom" data-remodal-id="modal-alert-news" data-remodal-options="hashTracking: false">
		<button data-remodal-action="close" class="remodal-close"></button>
		<div class="remodal-content"></div>
		<div class="remodal-buttons">
			<button data-remodal-action="confirm" class="remodal-confirm">{{ trans('admin.btn.OK') }}</button>
		</div>
	</div>
@endsection

@push('scripts')
	<script>
		(function($) {
			"use strict";

			var modal = $('[data-remodal-id="modal-delete-news"]').remodal({ closeOnConfirm: false });
			var modal_alert_js = $('[data-remodal-id="modal-alert-news"]');
			var modal_alert = modal_alert_js.remodal();
			var row_dt = false;

			jQuery.extend( jQuery.fn.dataTable.defaults, {
				language: {
					url: "../langs/dt-{{ Config::get('app.locale') }}.json"
				},
				autoWidth: false,
				pageLength: 25,
				dom: '<"top"f>rt<"bottom"ip><"clear">',
			} );

			$('#news_list').DataTable({
				columnDefs: [
					{ "orderData": 3, "targets": 4 },
				],
				columns: [
					@if(Auth::user()->isSuperAdmin())
					{ "type": "num" },
					@endif
					null,
					null,
					{ "searchable": false, className: "dt-center" },
					{ "searchable": false, "type": "date-euro", "visible": false },
					{ "searchable": false, className: "dt-center" },
					{ "searchable": false, "orderable": false },
				]
			});

			$('#news_list').on('click', '.remove_news',function() {
				var $this = $(this);
				var url = $this.data('url');
				modal.open();
				row_dt = $this.parents('tr');
			});

			$(document).on('confirmation', '.remodal[data-remodal-id="modal-delete-news"]', function () {
				if(row_dt === false) {
					return false;
				}

				$.ajax({
					url: row_dt.find('.remove_news').data('url'),
					method: "DELETE",
					success: function(data){
						modal_alert_js.find('.remodal-content').html(data);
						$('#news_list').DataTable().row(row_dt).remove().draw();
					},
					error: function(data, json) {
						modal_alert_js.find('.remodal-content').html(data.responseJSON);
					},
					complete: function(data) {
						modal_alert.open();
						row_dt = false;
					}
				});
			});

		})(jQuery);
	</script>
@endpush