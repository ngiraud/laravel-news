<?php

namespace NGiraud\News\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use NGiraud\News\Models\Comment;
use NGiraud\News\Models\News;
use NGiraud\News\Requests\CommentRequestAdmin;

class CommentController extends Controller {
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param CommentRequestAdmin $request
	 * @param $news_id
	 *
	 * @param int $parent_id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(CommentRequestAdmin $request, $news_id, $parent_id = 0) {
		if(!$request->ajax()) {
			exit;
		}
		$comment = Comment::create($request->all());

		if($parent_id == 0) {
			$view = view('news::admin.comments.unique', [
				'comment' => $comment,
				'news'    => $comment->news,
			])->render();
		}
		else {
			$news = News::withCount('allComments')->with('allComments')->find($news_id);
			$comments = $news->allComments->sortByDesc('updated_at')->groupBy('parent_id');

			$view = view('news::admin.comments.list', [
				'comments' => $comments,
				'parent'   => $parent_id,
				'news'     => $news,
			])->render();
		}

		return response()->json([
			'msg'  => trans("news::comments.flash.created.ok"),
			'view' => $view,
		]);
	}

	public function approve(Request $request, $news_id, $comment_id) {
		if(!$request->ajax()) {
			exit;
		}

		$comment = Comment::where([ 'id' => $comment_id, 'news_id' => $news_id ])->first();

		if(!is_null($comment)) {
			$comment->update([ 'approved_status' => 1 ]);

			return response()->json(trans("news::comments.flash.approve.ok"));
		}

		return response()->json(trans("news::comments.flash.approve.error"), 422);
	}

	public function disapprove(Request $request, $news_id, $comment_id) {
		if(!$request->ajax()) {
			exit;
		}

		$comment = Comment::where([ 'id' => $comment_id, 'news_id' => $news_id ])->first();

		if(!is_null($comment)) {
			$comment->update([ 'approved_status' => 0 ]);

			return response()->json(trans("news::comments.flash.disapprove.ok"));
		}

		return response()->json(trans("news::comments.flash.disapprove.error"), 422);
	}

	//	public function get($news_id) {
	//		$news = News::find($news_id);
	//
	//		return response()->json($news->comments);
	//	}
}
