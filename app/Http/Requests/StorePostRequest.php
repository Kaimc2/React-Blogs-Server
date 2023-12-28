<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'min:3', 'max:50', Rule::unique('posts')->ignore($this->post)],
            // 'slug' => ['required', Rule::unique('posts')->ignore($this->post)],
            'body' => ['required', 'min:3'],
            'category_id' => ['required'],
            'author_id' => ['required'],
            'thumbnail' => ['required', 'image', 'mimes:png,jpg,jpeg'],
        ];
    }
}
