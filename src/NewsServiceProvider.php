<?php

namespace NGiraud\News;

use Illuminate\Support\ServiceProvider;
use NGiraud\News\Models\Comment;
use NGiraud\News\Models\News;
use NGiraud\News\Observers\CommentObserver;
use NGiraud\News\Observers\NewsObserver;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;

class NewsServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() {
		$this->loadViewsFrom(__DIR__.DIRECTORY_SEPARATOR.'Views', 'news');
		$this->loadMigrationsFrom(__DIR__.DIRECTORY_SEPARATOR.'Migrations', 'news');
		$this->loadTranslationsFrom(__DIR__.DIRECTORY_SEPARATOR.'Translations', 'news');
		
		// Observer
		News::observe(NewsObserver::class);
		Comment::observe(CommentObserver::class);
		
		$this->publishes([
			__DIR__.DIRECTORY_SEPARATOR.'Views' => resource_path('views/vendor/news'),
			__DIR__.DIRECTORY_SEPARATOR.'Translations' => resource_path('lang/vendor/news'),
			__DIR__.DIRECTORY_SEPARATOR.'Public' => resource_path('assets/vendor/news'),
		], 'news');
		
		$this->publishes([
			__DIR__.DIRECTORY_SEPARATOR.'Plugins' => resource_path('assets/vendor/news/plugins'),
		], 'news_plugins');
		
		if(!$this->app->routesAreCached()) {
			include __DIR__.DIRECTORY_SEPARATOR.'routes.php';
		}
	}
	
	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->make('NGiraud\News\Controllers\Admin\NewsController');
		$this->app->register(MediaLibraryServiceProvider::class);
	}
}
