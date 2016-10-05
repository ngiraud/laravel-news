<?php

namespace NGiraud\News\Models;

//use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Utils;

class News extends Model implements HasMediaConversions {
	use Notifiable, HasMediaTrait, SoftDeletes;
	
	protected $fillable = [ 'user_id', 'title', 'slug', 'content', 'is_published', 'published_at' ];
	protected $dates = [ 'created_at', 'updated_at', 'published_at', 'deleted_at' ];
	protected $appends = [ 'url_image' ];
	
	// Attributes
	public function setTitleAttribute($value) {
		$this->attributes['title'] = $value;
		$this->attributes['slug']  = Str::slug($value);
		
		if(!App::runningInConsole()) {
			$this->attributes['user_id'] = Auth::user()->id;
		}
	}
	
	public function setIsPublishedAttribute($value) {
		$this->attributes['is_published'] = $value;
		/*if($value == 1) {
			$this->attributes['published_at'] = Carbon::now();
		}*/
	}
	
	public function getUrlImageAttribute() {
		$mediaItems = $this->getMedia();
		if(!empty($mediaItems[0]->file_name)) {
			return $mediaItems[0]->file_name;
		}
		
		return '';
	}
	
	// SCOPES
	public function scopePublished($query, $limit = false) {
		$the_query = $query
			->where('is_published', 1)
			->whereRaw('published_at < NOW()')
			->whereRaw('published_at != "0000-00-00 00:00:00"')
			->orderBy('published_at', 'desc');
		if($limit !== false) {
			return $the_query->take($limit);
		}
		
		return $the_query;
	}
	
	public function scopeUnpublished($query, $limit = false) {
		$the_query = $query->where('is_published', 0);
		if($limit !== false) {
			return $the_query->take($limit);
		}
		
		return $the_query;
	}
	
	public function scopeUser($query, $user_id = false) {
		$the_query = $query->where('user_id', $user_id);
		
		return $the_query;
	}
	
	public static function getExcerpt($text, $number_of_words = 50, $addurlmore = false, $url = '#', $readmore = '...') {
		preg_match('/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $text, $matches);
		
		if(!empty($matches[1])) {
			$iframe = '<iframe style="width: 100%;" allowfullscreen="allowfullscreen" src="'.$matches[1].'"></iframe>';
			
			return $iframe.Utils::excerpt_content($text, $number_of_words, $addurlmore, $url, $readmore);
		} else {
			return Utils::excerpt_content($text, $number_of_words, $addurlmore, $url, $readmore);
		}
	}
	
	public function getUrlImage($type = false) {
		$mediaItems = $this->getMedia();
		if(!$mediaItems->isEmpty()) {
			if($type !== false && file_exists($mediaItems[0]->getUrl($type))) {
				return $mediaItems[0]->getUrl($type);
			}
			return $mediaItems[0]->getUrl();
		}
		
		return '';
	}
	
	/**
	 * Get the comments for the news
	 */
	public function comments() {
		return $this->hasMany('NGiraud\News\Models\Comment')->where('parent_id', 0)->orderBy('updated_at', 'desc');
	}
	
	/**
	 * Get the comments for the news
	 */
	public function allComments() {
		return $this->hasMany('NGiraud\News\Models\Comment');
	}
	
	public function commentsApproved() {
		return $this
			->hasMany('NGiraud\News\Models\Comment')
			->where('approved_status', 1)
			->orderBy('updated_at', 'desc');
	}
	
	public function registerMediaConversions() {
		$this->addMediaConversion('thumb')
		     ->setManipulations([ 'w' => 368, 'h' => 232 ])
		     ->nonQueued();
	}
}
