<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'required|string',
            'treem_size' => 'required|string',
            'page_count' => 'required|numeric',
            'format' => 'required|string',
            'bleed_file' => 'required|string',
            'category' => 'required|string',
            'chapters' => 'required|string',
            'text_style' => 'required|string',
            'font_size' => 'required|numeric',
            'add_page_num' => 'required|string',
            'book_intro' => 'required|string',
            'copyright_page' => 'required|string',
            'table_of_contents' => 'required|string',
        ];
    }
}
