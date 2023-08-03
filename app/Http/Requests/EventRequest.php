<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
            'title' => 'required|string',
            'description' => 'required|string',
            'location' => 'required|string',
            'category' => 'required|string|exists:App\Models\Category,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'type' => 'required|string',
            'capacity' => 'required|integer',
            'available_seats' => 'nullable|integer',
            'price' => 'nullable|integer',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'time' => 'required|string',
        ];
    }
}
