<?php

namespace NGiraud\News\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;

class CommentRequestAdmin extends AbstractCommentRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}
	
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return parent::rules();
	}
	
	public function all() {
		$attributes = parent::all();
		$attributes['author_email'] = Auth::user()->email;
		$attributes['author_name'] = Auth::user()->name;
		$attributes['user_id'] = Auth::user()->id;
		
		return $attributes;
	}
}
