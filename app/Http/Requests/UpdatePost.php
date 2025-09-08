<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\EnumClass;

class UpdatePost extends FormRequest
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
            "title" => "nullable|string|unique:posts,title,". $this->route('postId'),
            "categoryId" => "nullable|integer|exists:categories,id",
            "status" => [Rule::in(EnumClass::statuses())],
            "tagIds" => "nullable|array",
            "tagIds.*" => "integer|exists:tags,id",
            "coverPhoto" => "nullable|image|mimes:png,jpg,webp,jpeg",
            "coverPhotoRemoved" => "boolean",
            "preview" => "nullable|string",
            "content" => "nullable|string"
        ];
    }
}
