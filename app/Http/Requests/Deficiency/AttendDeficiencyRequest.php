<?php

namespace App\Http\Requests\Deficiency;

use Illuminate\Foundation\Http\FormRequest;

class AttendDeficiencyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'inspection_id' => ['required'],
            'comment' => ['required', 'string', 'max:255'],
            'action_date' => ['required', 'date']
        ];
    }
}
