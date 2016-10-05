<?php

namespace NGiraud\News\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest {
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
		// Default value is_published checkbox
		$this->merge([ 'is_published' => $this->input('is_published', 0) ]);
		
		return [
			'title'        => 'required|min:5',
			'content'      => 'required|min:10',
			'is_published' => 'boolean',
			'published_at' => 'required|date_format:Y-m-d',
			'url_image'    => 'required_unless:_method,PUT|image|maxfilesize',
		];
	}
}
