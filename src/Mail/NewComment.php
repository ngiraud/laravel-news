<?php

namespace NGiraud\News\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use NGiraud\News\Models\Comment;

class NewComment extends Mailable implements ShouldQueue {
	use Queueable, SerializesModels;
	
	public $comment;
	
	/**
	 * Create a new message instance.
	 *
	 * @param $user
	 * @param Comment $comment
	 */
	public function __construct(Comment $comment) {
		$this->comment = $comment;
	}
	
	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
		return $this->from(config('mail.from'))
						->subject(trans('news::comments.notifications.front.subject'))
						->view('notifications::email')
						->text('notifications::email-plain')
						->with([
							'level' => 'default',
							'actionUrl' => route('front.news.show', $this->comment->news->slug).'#comment-'.$this->comment->id,
							'actionText' => trans('news::comments.notifications.btn.go'),
							'greeting' => trans('news::comments.notifications.front.added', [ 'news_title' => $this->comment->news->title ]),
							'introLines' => [
								trans('news::comments.notifications.admin.author', [
									'author_name'  => $this->comment->author_name,
									'author_email' => $this->comment->author_email,
								]),
								trans('news::comments.notifications.admin.date', [ 'date' => $this->comment->updated_at->toDateTimeString() ]),
								'',
								trans('news::comments.notifications.admin.content'),
								$this->comment->content,
							],
							'outroLines' => []
						]);
	}
}
