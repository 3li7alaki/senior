<?php

namespace App\Http\Requests\Evaluation;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;

class EvaluationRequest extends FormRequest
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
            'form_id' => 'required|integer|exists:forms,id',
            'done' => 'required|boolean',
            'pass' => 'required_if:done,true|boolean',
            'note' => 'nullable|string|max:255',
            'date_1' => 'nullable|date',
            'date_2' => 'nullable|date|after_or_equal:date_1',
            'date_3' => 'nullable|date|after_or_equal:date_2',
            'questions' => 'required|array|min:1',
            'questions.*.question_id' => 'required|integer|exists:questions,id',
            'questions.*.answer' => 'required_if:done,true',
            'questions.*.note' => 'nullable|string|max:255',
            'path' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:10240',
            'users' => 'nullable|array',
            'users.*' => 'integer|exists:users,id',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Response::failure($validator->errors()->first()));
    }
}
