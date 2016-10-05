<?php

namespace NGiraud\News\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AbstractCommentRequest extends FormRequest {
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
		return [
			'comment_content' => 'required|min:2',
		];
	}
	
	public function all() {
		$attributes              = parent::all();
		$attributes['content']   = isset($attributes['comment_content']) ? $attributes['comment_content'] : $attributes['content'];
		$attributes['news_id']   = $this->route()->getParameter('news_id');
		$attributes['parent_id'] = $this->route()->getParameter('parent_id');
		
		return $attributes;
	}
	
	public function formatErrors(Validator $validator) {
		if(!$this->ajax()) {
			return $validator->getMessageBag()->toArray();
		}
		
		return [ 0 => view('partials.errors-admin')->withErrors([ 'errors' => $validator->errors()->all() ])->render() ];
	}
}
