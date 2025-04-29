<?php

namespace App\Http\Requests\Program;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;

class AdminChildProgramRequest extends FormRequest
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
                'status_id' => 'nullable|integer|exists:statuses,id',
                'note' => 'nullable|string|max:255',
                'date' => 'nullable|date',
                'created_at' => 'nullable|date',
                'schedule' => 'nullable|array',
                'schedule.*.day' => 'nullable|string|in:Saturday,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday',
                'schedule.*.from' => 'nullable|date_format:H:i:s',
                'schedule.*.to' => 'nullable|date_format:H:i:s',
            ];
        }
        return [
            'program_id' => 'required|integer|exists:programs,id',
            'status_id' => 'nullable|integer|exists:statuses,id',
            'note' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'active' => 'required|boolean',
            'schedule' => 'nullable|array',
            'schedule.*.day' => 'nullable|string|in:Saturday,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday',
            'schedule.*.from' => 'nullable|date_format:H:i:s',
            'schedule.*.to' => 'nullable|date_format:H:i:s',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Response::failure($validator->errors()->first()));
    }
}
