<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\EnumClass;

class SavePost extends FormRequest
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
            "title" => "required|string|unique:posts,title",
            "categoryId" => "required|integer|exists:categories,id",
            "status" => [Rule::in(EnumClass::statuses())],
            "tagIds" => "nullable|array",
            "tagIds.*" => "integer|exists:tags,id",
            "coverPhoto" => "nullable|image|mimes:png,jpg,webp,jpeg",
            "preview" => "required|string",
            "content" => "required|string"
        ];
    }
}
