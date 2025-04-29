<?php

namespace App\Http\Requests\Child;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;

class GuardianChildRequest extends FormRequest
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
                'birth_place' => 'required|string|max:255',
                'gender' => 'required|in:male,female',
                'nationality_id' => 'required|integer|exists:nationalities,id',
                'cpr' => 'required|string|max:255|unique:children,cpr,' . $this->route('child')->id,
                'other_number' => 'required|string|max:255',
                'guardian_relation' => 'required|string|max:255',
                'lives_with' => 'required|string|max:255',
                'ministry_registered' => 'boolean',
                'other_center' => 'nullable|string|max:255',
                'other_center_year' => 'nullable|string|max:255',
                'building' => 'required|string|max:255',
                'apartment' => 'nullable|string|max:255',
                'street' => 'required|string|max:255',
                'block' => 'required|string|max:255',
                'area' => 'required|string|max:255',
                'photo' => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
                'national_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            ];
        }
        return [
            'full_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before_or_equal:today',
            'birth_place' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'nationality_id' => 'required|integer|exists:nationalities,id',
            'cpr' => 'required|string|max:255|unique:children,cpr',
            'other_number' => 'required|string|max:255',
            'guardian_relation' => 'required|string|max:255',
            'lives_with' => 'required|string|max:255',
            'other_center' => 'nullable|string|max:255',
            'other_center_year' => 'nullable|string|max:255',
            'ministry_registered' => 'boolean',
            'building' => 'required|string|max:255',
            'apartment' => 'nullable|string|max:255',
            'street' => 'required|string|max:255',
            'block' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'photo' => 'required|file|mimes:jpg,jpeg,png|max:10240',
            'national_id' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Response::failure($validator->errors()->first()));
    }
}
