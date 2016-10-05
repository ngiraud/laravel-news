<?php

namespace NGiraud\News\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;

class CommentRequest extends AbstractCommentRequest {
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
		$rules = parent::rules();
		
		return array_merge($rules, [
			'author_name'     => 'required|min:2',
			'author_email'    => 'required|email',
		]);
	}
	
	public function response(array $errors) {
		if($this->ajax() || $this->wantsJson()) {
			return new JsonResponse($errors, 422);
		}
		
		return $this->redirector->to($this->getRedirectUrl().'#new-comment')
		                        ->withInput($this->except($this->dontFlash))
		                        ->withErrors($errors, $this->errorBag);
	}
}
