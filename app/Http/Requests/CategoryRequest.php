<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            $rules['name'][] = 'unique:categories,name';
        } else if ($method === 'patch') {
            $activity = $this->route('category');

            if ($activity->name !== $this->get('name')) {
                $rules['name'][] = 'unique:categories,name';
            }
        }

        return $rules;
    }
}
