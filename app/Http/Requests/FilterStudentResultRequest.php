<?php

namespace App\Http\Requests;

use App\Utils\Utils;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterStudentResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === Utils::ROLE_STUDENT;
    }

    public function rules(): array
    {
        $allowedSemesters = array_keys(Utils::SEMESTERS);

        return [
            'session_filter' => ['required', Rule::in([
                Utils::SELECTION_FILTER_ALL,
                Utils::SELECTION_FILTER_SELECTED,
                Utils::SELECTION_FILTER_CURRENT,
            ])],
            'semester_filter' => ['required', Rule::in([
                Utils::SELECTION_FILTER_ALL,
                Utils::SELECTION_FILTER_SELECTED,
                Utils::SELECTION_FILTER_CURRENT,
            ])],
            'sessions' => ['required_if:session_filter,' . Utils::SELECTION_FILTER_SELECTED, 'array'],
            'sessions.*' => ['integer', Rule::exists('school_sessions', 'id')],
            'semesters' => ['required_if:semester_filter,' . Utils::SELECTION_FILTER_SELECTED, 'array'],
            'semesters.*' => ['string', Rule::in($allowedSemesters)],
        ];
    }
}
