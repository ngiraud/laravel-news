@if(Auth::user()->hasPermission('manage_news'))
	<li>
		<?php $is_linked = (Route::currentRouteName() == 'admin.news.index'
		                    || Route::currentRouteName() == 'admin.news.create'
		                    || (isset($news) && Route::currentRouteName() == 'admin.news.edit'));
		?>
		<a href="#" class="parent {{ ($is_linked) ? 'active' : '' }}">
			<i class="fa fa-newspaper-o"></i>{{ trans('news::messages.news_title') }}
			<i class="expand-menu fa {{ ($is_linked) ? 'fa-chevron-up' : 'fa-chevron-down' }}"></i>
		</a>
		
		<ul class="{{ ($is_linked) ? '' : 'hide' }}">
			@section('submenu.news')
				<li>
					<a href="{{ route('admin.news.index') }}" class="{{ (Route::currentRouteName() == 'admin.news.index') ? 'active' : '' }}">
						<i class="fa fa-chevron-right"></i>{{ trans('news::messages.menu.title.list') }}
					</a>
				</li>
				<li>
					<a href="{{ route('admin.news.create') }}" class="{{ (Route::currentRouteName() == 'admin.news.create') ? 'active' : '' }}">
						<i class="fa fa-chevron-right"></i>{{ trans('news::messages.menu.title.add') }}
					</a>
				</li>
			@show
		</ul>
	</li>
@endif