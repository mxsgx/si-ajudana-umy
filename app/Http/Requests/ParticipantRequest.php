<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParticipantRequest extends FormRequest
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
            $rules['name'][] = 'unique:participants,name';
        } else if ($method === 'patch') {
            $participant = $this->route('participant');

            if ($participant->name !== $this->get('name')) {
                $rules['name'][] = 'unique:participants,name';
            }
        }

        return $rules;
    }
}
