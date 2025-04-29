<?php

namespace App\Http\Requests\Program;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;

class ProgramRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'active' => 'required|boolean',
            'all_diagnoses' => 'required|boolean',
            'all_ages' => 'required|boolean',
            'gender' => 'required|in:male,female,all',
            'ministry_registered' => 'required|boolean',
            'days' => 'required|array',
            'days.*' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s',
            'min_age' => 'required_if:all_ages,false',
            'max_age_male' => 'required_if:all_ages,false',
            'max_age_female' => 'required_if:all_ages,false',
            'diagnoses' => 'required_if:all_diagnoses,false|array|min:1',
            'diagnoses.*' => 'required_if:all_diagnoses,false|integer|distinct|exists:diagnoses,id,type,preset',
            'attachments' => 'nullable|array|max:3',
            'attachments.*.name' => 'required|string|max:255',
            'attachments.*.path' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:10240',
            'attachments.*.description' => 'nullable|string|max:255',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Response::failure($validator->errors()->first()));
    }
}
