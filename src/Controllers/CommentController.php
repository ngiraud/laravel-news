<?php

namespace NGiraud\News\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use NGiraud\News\Models\Comment;
use NGiraud\News\Models\News;
use NGiraud\News\Requests\CommentRequest;

class CommentController extends Controller {
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param CommentRequest|Request $request
	 * @param $news_id
	 *
	 * @param $parent_id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(CommentRequest $request, $news_id, $parent_id = 0) {
		$comment = Comment::create($request->all());
		
		return redirect(route('front.news.show', [ 'slug' => $comment->news->slug ]).'#comment-'.$comment->id);
		
//		if(!$request->ajax()) {
//			return redirect(back());
//			return redirect(route('front.news.unique', [ 'slug' => $news->slug ]).'#comment-'.$comment->id);
//		} else {
//			$parent_comment = Comment::where('id', $parent_id)->first();
//			return response()->json(view('news::admin.comments.list', [
//				'comments' => is_null($parent_comment) ? [] : $parent_comment->children,
//				'is_child' => is_null($parent_comment) ? false : true,
//			])->render());
//		}
		
	}
}
