<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LecturerRequest extends FormRequest
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
            'nip' => ['nullable'],
            'nik' => ['nullable'],
            'birth_date' => ['required', 'date', 'date_format:Y-m-d'],
            'birth_place' => ['nullable', 'string'],
            'study_id' => ['required', 'exists:studies,id'],
            'address' => ['nullable', 'string'],
        ];

        if ($method === 'post') {
            $rules['nip'][] = 'unique:lecturers,nip';
            $rules['nik'][] = 'unique:lecturers,nik';
        } else if ($method === 'patch') {
            $nip = $this->get('nip');
            $nik = $this->get('nik');
            $lecturer = $this->route('lecturer');

            if (!$nip && $lecturer->nip != $nip) {
                $rules['nip'][] = 'unique:lecturers,nip';
            }
            if (!$nik && $lecturer->nik != $nik) {
                $rules['nik'][] = 'unique:lecturers,nik';
            }
        }

        return $rules;
    }
}
