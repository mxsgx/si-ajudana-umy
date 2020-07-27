<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $roles = collect(User::getModel()->roles)->keys();
        $rules = [
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['string'],
            'role' => ['required', 'in:'.$roles->join(',')],
            'study_id' => ['required_if:role,head-of-program-study', 'exists:studies,id'],
            'lecturer_id' => ['required_if:role,lecturer', 'exists:lecturers,id'],
            'faculty_id' => ['required_if:role,dean', 'exists:faculties,id'],
        ];

        if ($method === 'post') {
            $rules['password'][] = 'required';
            $rules['email'][] = 'unique:users,email';
        } else {
            if ($method === 'patch') {
                $rules['password'][] = 'nullable';
                $email = $this->get('email');
                $user = $this->route('user');

                if ($user->email !== $email) {
                    $rules['email'][] = 'unique:users,email';
                }
            }
        }

        return $rules;
    }
}
