<?php
namespace NGiraud\News\Observers;

use Illuminate\Support\Facades\Input;
use NGiraud\News\Models\News;

class NewsObserver {
	/**
	 * Listen to the User updating event.
	 * Only update image when a new one has been loaded
	 * @param News $news
	 */
	public function saved(News $news) {
		if(Input::hasFile('url_image')) {
			$news->clearMediaCollection();
			$news->addMedia(Input::file('url_image'))
			     ->preservingOriginal()
			     ->toMediaLibrary();
		}
	}
	/**
	 * Listen to the User deleting event.
	 *
	 * @param News $news
	 *
	 * @internal param User $user
	 *
	 */
	public function deleting(News $news) {
		$news->allComments()->delete();
	}
}