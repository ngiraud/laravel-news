<?php
Route::group([
	'namespace' => 'NGiraud\News\Controllers\Admin',
	'prefix' => 'admin',
	'middleware' => [ 'web', 'auth' ]
], function() {
	Route::resource('news', 'NewsController', [ 'except' => [ 'show' ], 'as' => 'admin' ]);
	Route::post('/news/publish', [ 'as' => 'admin.publish_news', 'uses' => 'NewsController@publish' ]);
	
	Route::post('/news/{news_id}/comment/{parent_id?}/store', [ 'as' => 'admin.comment.store', 'uses' => 'CommentController@store']);
	// Comment actions
	Route::post('/news/{news_id}/comment/{comment_id}/approve', [ 'as' => 'admin.comment.approve', 'uses' => 'CommentController@approve']);
	Route::post('/news/{news_id}/comment/{comment_id}/disapprove', [ 'as' => 'admin.comment.disapprove', 'uses' => 'CommentController@disapprove']);
});

Route::group([
	'namespace' => 'NGiraud\News\Controllers',
	'middleware' => [ 'web' ]
], function() {
	Route::get('/blog/{slug}', [ 'as' => 'front.news.show', 'uses' => 'NewsController@show']);
	Route::post('/news/{news_id}/comment/{parent_id?}/store', [ 'as' => 'front.comment.store', 'uses' => 'CommentController@store']);
});

//Route::get('/news/{news_id}/comments', [ 'uses' => 'NGiraud\News\Controllers\Admin\CommentController@get' ]);