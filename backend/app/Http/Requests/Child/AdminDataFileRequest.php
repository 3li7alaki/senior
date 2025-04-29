<?php

namespace App\Http\Requests\Child;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;

class AdminDataFileRequest extends FormRequest
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
            'father_name' => 'nullable|string|max:255',
            'father_job' => 'nullable|string|max:255',
            'father_nationality_id' => 'nullable|integer|exists:nationalities,id',
            'father_phone' => 'nullable|string|max:255',
            'father_work_phone' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'mother_job' => 'nullable|string|max:255',
            'mother_nationality_id' => 'nullable|integer|exists:nationalities,id',
            'mother_phone' => 'nullable|string|max:255',
            'mother_work_phone' => 'nullable|string|max:255',
            'house_phone' => 'nullable|string|max:255',
            'close_person_phone' => 'nullable|string|max:255',
            'siblings_count' => 'nullable|string',
            'sibling_order' => 'nullable|string',
            'father_age_at_birth' => 'nullable|integer|min:0',
            'mother_age_at_birth' => 'nullable|integer|min:0',
            'parent_relation' => 'nullable|string|max:255',
            'pregnancy_issue' => 'nullable|string',
            'birth_issue' => 'nullable|string',
            'familial_issue' => 'nullable|string',
            'heart_check' => 'required|boolean',
            'heart_check_date' => 'nullable|date|before_or_equal:today',
            'heart_check_result' => 'nullable|string',
            'thyroid_check' => 'required|boolean',
            'thyroid_check_date' => 'nullable|date|before_or_equal:today',
            'thyroid_check_result' => 'nullable|string',
            'sight_check' => 'required|boolean',
            'sight_check_date' => 'nullable|date|before_or_equal:today',
            'sight_check_result' => 'nullable|string',
            'hearing_check' => 'required|boolean',
            'hearing_check_date' => 'nullable|date|before_or_equal:today',
            'hearing_check_result' => 'nullable|string',
            'epileptic' => 'nullable|string',
            'breathing_issues' => 'nullable|string',
            'teeth_issues' => 'nullable|string',
            'surgeries_done' => 'nullable|string',
            'exams_applied' => 'nullable|string',
            'problems_faced' => 'nullable|string',
            'training_needed' => 'nullable|string',
            'other' => 'nullable|string',
            'father_national_id' => 'nullable|mimes:jpg,jpeg,png,pdf|max:10240',
            'mother_national_id' => 'nullable|mimes:jpg,jpeg,png,pdf|max:10240',
            'attachments' => 'nullable|array|max:4',
            'attachments.*.path' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:10240',
            'attachments.*.name' => 'nullable|string|max:255',
            'attachments.*.description' => 'nullable|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Response::failure($validator->errors()->first()));
    }
}
