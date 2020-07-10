<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FacultyRequest extends FormRequest
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
        ];

        if ($method === 'post') {
            $rules['name'][] = 'unique:faculties,name';
        } else if ($method === 'patch') {
            $faculty = $this->route('faculty');

            if ($faculty->name !== $this->get('name')) {
                $rules['name'][] = 'unique:faculties,name';
            }
        }

        return $rules;
    }
}
