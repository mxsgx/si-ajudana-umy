<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinancialRequest extends FormRequest
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
            $rules['name'][] = 'unique:financials,name';
        } else if ($method === 'patch') {
            $financial = $this->route('financial');

            if ($financial->name !== $this->get('name')) {
                $rules['name'][] = 'unique:financials,name';
            }
        }

        return $rules;
    }
}
