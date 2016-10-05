<?php
namespace NGiraud\News\Observers;

use App\User;
use DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use NGiraud\News\Mail\NewComment;
use NGiraud\News\Models\Comment;
use NGiraud\News\Notifications\NewCommentAdmin;

class CommentObserver {
	/**
	 * Listen to the User creating event.
	 *
	 * @param Comment $comment
	 *
	 */
	public function creating(Comment $comment) {
		$comment->approved_status = 1;
		
		if(empty($comment->user_id)) {
			$user = User::where('email', $comment->author_email)->first();
			if(!is_null($user)) {
				$comment->user_id = $user->id;
			}
		}
	}
	
	/**
	 * Listen to the User created event.
	 *
	 * @param Comment $comment
	 *
	 */
	public function created(Comment $comment) {
		$news = $comment->news;
		$author_email = $comment->author_email;
		
		// Send notifications to admin
		$users_admin = User::permissions('manage_news')->get();
		$users_admin->each(function($user, $k) use ($comment) {
			$user->notify(new NewCommentAdmin($comment));
		});
		
		// Send email for concerned users
		$news_email = DB::table('news_commented_email')
							->leftJoin('users', 'news_commented_email.email', '=', 'users.email')
							->select('news_commented_email.*', 'users.id')
							->where([
								['news_id', $news->id],
							])
							->get();
		
		$emails_to_be_notified = $news_email->reject(function($value, $key) use ($author_email, $users_admin) {
			return $value->email == $author_email || $users_admin->contains('email', $value->email);
		});
		
		$emails_to_be_notified->each(function($obj, $key) use ($comment) {
			Mail::to($obj->email)->send(new NewComment($comment));
		});
		
		// Add author in table "news_commented_email" if not in
		$news_notif_author = $news_email->where('email', $author_email)->first();
		if(is_null($news_notif_author)) {
			DB::table('news_commented_email')->insert([
				'news_id'   => $news->id,
				'email'   => $author_email,
				'token' => str_random(30),
			]);
		}
	}
}