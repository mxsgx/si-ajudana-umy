<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = strtolower($this->getMethod());
        $rules = [
            'name' => ['required', 'string'],
            'faculty_id' => ['required', 'exists:faculties,id'],
        ];

        if ($method === 'post') {
            $rules['name'][] = 'unique:studies,name';
        } else if ($method === 'patch') {
            $study = $this->route('study');

            if ($study->name !== $this->get('name')) {
                $rules['name'][] = 'unique:studies,name';
            }
        }

        return $rules;
    }
}
