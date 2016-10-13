<?php

namespace NGiraud\News\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model {
	use SoftDeletes;
	
	protected $fillable = [
		'news_id',
		'author_name',
		'author_email',
		'author_url',
		'user_id',
		'approved_status',
		'content',
		'parent_id',
	];
	
	protected $dates = [ 'created_at', 'updated_at', 'deleted_at' ];
	
	/**
	 * Get the news that owns the comment.
	 */
//	public function news() {
//		return $this->belongsTo('App\Models\News');
//	}
	
	public function children() {
		return $this->hasMany('NGiraud\News\Models\Comment', 'parent_id', 'id')->orderBy('updated_at', 'asc');
	}
	
	public function parent() {
		return $this->belongsTo('NGiraud\News\Models\Comment', 'parent_id', 'id');
	}
	
	public function news() {
		return $this->belongsTo('NGiraud\News\Models\News');
	}

	public function user() {
		return $this->belongsTo('App\User');
	}
}
