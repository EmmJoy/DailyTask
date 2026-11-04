<?php

namespace App\Http\Requests;

use App\Models\DailyRecord;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $amount = $this->input('amount');

        $this->merge([
            'notes' => $this->filled('notes') ? $this->input('notes') : null,
            'mood' => $this->filled('mood') ? $this->input('mood') : null,
            'amount' => ($amount === null || $amount === '') ? null : $amount,
            'direction' => ($amount === null || $amount === '') ? null : ($this->filled('direction') ? $this->input('direction') : null),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'record_date' => ['required', 'date'],
            'category' => ['required', Rule::in(array_keys(DailyRecord::CATEGORIES))],
            'title' => ['required', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'mood' => ['nullable', Rule::in(array_keys(DailyRecord::MOODS))],
            'amount' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'direction' => ['nullable', 'required_with:amount', Rule::in(array_keys(DailyRecord::DIRECTIONS))],
        ];
    }
}
