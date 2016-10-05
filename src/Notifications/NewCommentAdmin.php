<?php

namespace NGiraud\News\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use NGiraud\News\Models\Comment;

class NewCommentAdmin extends Notification implements ShouldQueue {
	use Queueable;
	/**
	 * @var Comment
	 */
	private $comment;
	
	/**
	 * Create a new notification instance.
	 *
	 * @param Comment $comment
	 *
	 */
	public function __construct(Comment $comment) {
		$this->comment = $comment;
	}
	
	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed $notifiable
	 *
	 * @return array
	 */
	public function via($notifiable) {
		return [ 'mail', 'database' ];
	}
	
	/**
	 * Get the mail representation of the notification.
	 *
	 * @param  mixed $notifiable
	 *
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable) {
		Log::info('Showing comment profile for user: '.$notifiable->id);
		return (new MailMessage)
			->subject(trans('news::comments.notifications.admin.subject'))
			->level('error')
			->greeting(trans('news::comments.notifications.admin.added', [ 'news_title' => $this->comment->news->title ]))
			->line(trans('news::comments.notifications.admin.author', [
				'author_name'  => $this->comment->author_name,
				'author_email' => $this->comment->author_email,
			]))
			->line(trans('news::comments.notifications.admin.date', [ 'date' => $this->comment->updated_at->toDateTimeString() ]))
			->line('')
			->line(trans('news::comments.notifications.admin.content'))
			->line($this->comment->content)
			->action(trans('news::comments.notifications.btn.moderate'), route('admin.news.edit', $this->comment->news).'#comment-'.$this->comment->id);
	}
	
	/**
	 * Get the array representation of the notification.
	 *
	 * @param  mixed $notifiable
	 *
	 * @return array
	 */
	public function toArray($notifiable) {
		return $this->comment->toArray();
	}
}
