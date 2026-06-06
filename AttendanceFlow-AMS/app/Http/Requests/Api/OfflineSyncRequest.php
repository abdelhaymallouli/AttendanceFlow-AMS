<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class OfflineSyncRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null
            && ($this->user()->hasRole('student') || $this->user()->hasRole('admin'));
    }

    public function rules(): array
    {
        return [
            'entries'                       => ['required', 'array', 'min:1', 'max:50'],
            'entries.*.session_id'          => ['required', 'integer', 'exists:academic_sessions,id'],
            'entries.*.token'               => ['required', 'string', 'min:32', 'max:2048'],
            'entries.*.nonce'               => ['required', 'string', 'max:64'],
            'entries.*.latitude'            => ['nullable', 'numeric', 'between:-90,90'],
            'entries.*.longitude'           => ['nullable', 'numeric', 'between:-180,180'],
            'entries.*.accuracy'            => ['nullable', 'numeric', 'min:0', 'max:10000'],
            'entries.*.device_fingerprint'  => ['nullable', 'string', 'max:128'],
            'entries.*.client_timestamp'    => ['required', 'date'],
            'entries.*.client_ip'           => ['nullable', 'ip'],
        ];
    }
}
