<?php

namespace NGiraud\News\Controllers;

use App\Permission;
use App\Role;
use App\User;
use NGiraud\News\Models\News;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use NGiraud\News\Requests\NewsRequest;

class NewsController extends Controller {
	
	/**
	 * NewsController constructor.
	 */
	public function __construct() {
		Carbon::setLocale(config('app.locale'));
		setlocale(LC_TIME, config('app.locale'));
	}
	
	public function show($slug) {
		$news           = News::where('slug', $slug)->withCount('commentsApproved')->with('commentsApproved')->first();
		$comments = $news->allComments->sortByDesc('updated_at')->groupBy('parent_id');
		
		return view('news::front.news.show', compact('news', 'comments'));
	}
}
