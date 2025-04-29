<?php

namespace App\Http\Requests\Child;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;

class ChildDiagnoseRequest extends FormRequest
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
        if ($this->isMethod('PUT')) {
            return [
                'date' => 'required|date|before_or_equal:today',
                'institution' => 'required|string|max:255',
                'symptoms_age' => 'required|string',
                'symptoms' => 'required|string|max:255',
                'attachments' => 'nullable|array',
                'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            ];
        }
        return [
            'diagnose_id' => 'nullable',
            'name' => 'required_if:diagnose_id,null',
            'description' => 'required_if:diagnose_id,null',
            'date' => 'required|date|before_or_equal:today',
            'institution' => 'required|string|max:255',
            'symptoms_age' => 'required|string',
            'symptoms' => 'required|string|max:255',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Response::failure($validator->errors()->first()));
    }
}
