<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
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
        $rules = [
            'title' => 'required|min:3|max:50|unique:posts,title,' . $this->post->id . ',id',
            'body' => 'required',
            // 'slug' => ['required', Rule::unique('posts')->ignore($this->post)],
            'category_id' => ['required'],
        ];

        // Check if the 'thumbnail' key exists in the request
        if ($this->hasFile('thumbnail')) {
            // If it exists, add validation rules for the thumbnail
            $rules['thumbnail'] = 'image|mimes:jpeg,png,jpg';
        }

        return $rules;
    }
}
