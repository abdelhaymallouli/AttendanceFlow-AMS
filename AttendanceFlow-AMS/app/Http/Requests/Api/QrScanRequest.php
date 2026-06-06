<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class QrScanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null
            && ($this->user()->hasRole('student') || $this->user()->hasRole('admin'));
    }

    public function rules(): array
    {
        return [
            'token'               => ['required', 'string', 'min:32', 'max:2048'],
            'student_profile_id'  => ['required', 'integer', 'exists:student_profiles,id'],
            'latitude'            => ['required', 'numeric', 'between:-90,90'],
            'longitude'           => ['required', 'numeric', 'between:-180,180'],
            'accuracy'            => ['nullable', 'numeric', 'min:0', 'max:10000'],
            'device_fingerprint'  => ['nullable', 'string', 'max:128'],
            'client_timestamp'    => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'token.required' => 'QR token is required.',
            'latitude.between' => 'Latitude must be between -90 and 90.',
            'longitude.between' => 'Longitude must be between -180 and 180.',
            'student_profile_id.exists' => 'Invalid student profile.',
        ];
    }
}
