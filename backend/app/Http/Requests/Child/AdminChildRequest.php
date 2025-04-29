<?php

namespace App\Http\Requests\Child;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;

class AdminChildRequest extends FormRequest
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
        if ($this->method() === 'PUT') {
            return [
                'full_name' => 'required|string|max:255',
                'birth_date' => 'required|date|before_or_equal:today',
                'birth_place' => 'nullable|string|max:255',
                'gender' => 'required|in:male,female',
                'nationality_id' => 'nullable|integer|exists:nationalities,id',
                'cpr' => 'nullable|string|max:255|unique:children,cpr,' . $this->route('child')->id,
                'other_number' => 'nullable|string|max:255',
                'guardian_id' => 'nullable|integer|exists:users,id',
                'guardian_relation' => 'nullable|string|max:255',
                'lives_with' => 'nullable|string|max:255',
                'ministry_registered' => 'boolean',
                'other_center' => 'nullable|string|max:255',
                'other_center_year' => 'nullable|string|max:255',
                'building' => 'nullable|string|max:255',
                'apartment' => 'nullable|string|max:255',
                'street' => 'nullable|string|max:255',
                'block' => 'nullable|string|max:255',
                'area' => 'nullable|string|max:255',
                'photo' => 'nullable|mimes:jpg,jpeg,png|max:10240',
                'national_id' => 'nullable|mimes:jpg,jpeg,png,pdf|max:10240',
            ];
        }
        return [
            'full_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before_or_equal:today',
            'birth_place' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female',
            'nationality_id' => 'nullable|integer|exists:nationalities,id',
            'cpr' => 'nullable|string|max:255|unique:children,cpr',
            'other_number' => 'nullable|string|max:255',
            'guardian_id' => 'nullable|integer|exists:users,id',
            'guardian_relation' => 'nullable|string|max:255',
            'lives_with' => 'nullable|string|max:255',
            'ministry_registered' => 'boolean',
            'other_center' => 'nullable|string|max:255',
            'other_center_year' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'apartment' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'block' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'photo' => 'nullable|mimes:jpg,jpeg,png|max:10240',
            'national_id' => 'nullable|mimes:jpg,jpeg,png,pdf|max:10240',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Response::failure($validator->errors()->first()));
    }
}
