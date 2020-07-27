<?php

namespace App\Http\Requests;

use App\Submission;
use Illuminate\Foundation\Http\FormRequest;

class SubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $role = auth()->user()->role;
        $method = strtolower($this->getMethod());
        $statuses = collect(Submission::getModel()->statuses)->keys();
        $status = $this->get('status');
        $rules = [
            'activity_id' => ['required', 'exists:activities,id'],
            'name' => ['required', 'string'],
            'date_start' => ['required', 'date', 'date_format:Y-m-d'],
            'time_start' => ['nullable', 'date_format:H:i'],
            'date_end' => ['nullable', 'date', 'date_format:Y-m-d'],
            'time_end' => ['nullable', 'date_format:H:i'],
            'place' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'participant_id.*' => ['nullable', 'exists:participants,id'],
            'title' => ['required', 'string'],
            'writer' => ['required', 'string'],
            'schema' => ['nullable', 'string'],
            'grant' => ['nullable', 'string'],
            'financial_id.*' => ['nullable', 'exists:financials,id'],
            'financial_value.*' => ['nullable', 'integer'],
            'attachment.*' => ['nullable', 'file'],
        ];

        if ($role === 'admin') {
            $rules['lecturer_id'] = ['required', 'exists:lecturers,id'];
            $rules['status'] = ['required', 'in:'.$statuses->join(',')];

            if ($status !== 'unauthorized') {
                $rules['authorized_by'] = ['required', 'exists:users,id'];

                if ($status !== 'authorized') {
                    $rules['authorized_by_co_dean'] = ['required', 'exists:users,id'];

                    if ($status !== 'authorized-co-dean' &&
                        $status !== 'revision-co-dean' &&
                        $status !== 'rejected-co-dean') {
                        $rules['approved_by_co_dean'] = ['required', 'exists:users,id'];

                        if ($status === 'approved') {
                            $rules['approved_by'] = ['required', 'exists:users,id'];
                        }
                    }
                }
            }
        }

        if ($method === 'patch') {
            $rules['delete_attachment.*'] = ['nullable', 'exists:attachments,id'];
        }

        return $rules;
    }
}
