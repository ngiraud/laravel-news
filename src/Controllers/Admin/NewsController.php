<?php

namespace NGiraud\News\Controllers\Admin;

use Illuminate\Support\Collection;
use NGiraud\News\Models\Comment;
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
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$news = News::all();
		
		return view('news::admin.news.index', compact('news'));
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		$news = new News();
		
		return view('news::admin.news.create', compact('news'));
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 *
	 * @param NewsRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(NewsRequest $request) {
		$news = News::create($request->all());
		
		return redirect(route('admin.news.edit', $news))->with('success', trans('news::messages.flash.created'));
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$news           = News::withCount('allComments')->with('allComments')->find($id);
		$comments = $news->allComments->sortByDesc('updated_at')->groupBy('parent_id');
		
		return view('news::admin.news.edit', compact('news', 'comments'));
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param NewsRequest $request
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(NewsRequest $request, $id) {
		$news = News::findOrFail($id);
		$news->update($request->all());
		
		return redirect(route('admin.news.edit', $id))->with('success', trans('news::messages.flash.updated'));
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Request $request
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, $id) {
		if(!$request->ajax()) {
			exit;
		}
		
		$news    = News::findOrFail($id);
		$success = $news->delete();
		
		if($success == 1) {
			return response()->json(trans("news::messages.flash.deleted.ok"));
		}
		
		return response()->json(trans('news::messages.flash.deleted.error'), 422);
	}
	
	public function publish(Request $request) {
		if(!$request->ajax()) {
			exit;
		}
		
		$success = false;
		
		$news = News::findOrFail($request->get('id'));
		
		if($news !== null) {
			$news->update([
				'is_published' => 1,
				'published_at' => new Carbon(),
			]);
			$success = true;
		}
		
		if($success == 1) {
			return response()->json(trans("news::messages.flash.published.ok"));
		}
		
		return response()->json(trans('news::messages.flash.published.error'), 422);
	}
}
