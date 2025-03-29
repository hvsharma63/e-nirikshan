<?php

namespace App\Http\Requests\Inspection;

use App\Enums\InspectionDayPeriodEnum;
use App\Enums\InspectionStatusEnum;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class InspectionCreateRequest extends FormRequest
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
            'location' => ['required', 'string'],
            'datetime' => ['required', 'date'],
            'address' => ['required', 'string', 'max:255'],
            'is_draft' => ['required', 'boolean'],
            'no_deficiencies_found' => ['required', 'boolean'],
            'deficiencies' => ['required_if:no_deficiencies_found,false', 'array'],
            'deficiencies.*.note' => ['required', 'string'],
            'deficiencies.*.pertains_to' => ['required']
        ];
    }


    public function messages(): array
    {
        return [
            'deficiencies.required_if' => 'The deficiency field is required.',
            'deficiencies.*.note'=> 'Please mention the deficiency note.',
            'deficiencies.*.pertains_to'=> 'Please select an officer.',
        ];
    }


    public function validated($key = null, $default = null): array
    {
        return array_merge(parent::validated($key, $default), [
            'attended_by' => Auth::id(),
            'day_period' => InspectionDayPeriodEnum::getPeriodFromDateTime(Carbon::parse($this->input('datetime'))),
            'status' => $this->get('is_draft') ? InspectionStatusEnum::DRAFT() : InspectionStatusEnum::PROGRESS(),
        ]);
    }
}
