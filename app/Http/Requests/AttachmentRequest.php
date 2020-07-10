<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttachmentRequest extends FormRequest
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
            $rules['name'][] = 'unique:attachments,name';
        } else if ($method === 'patch') {
            $attachment = $this->route('attachment');

            if ($attachment->name !== $this->get('name')) {
                $rules['name'][] = 'unique:attachments,name';
            }
        }

        return $rules;
    }
}
