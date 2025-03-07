<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeviceRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'deviceId' => ['required', 'max:255'],
            'activationCode' => ['nullable', 'max:255', Rule::unique('devices')->ignore($this->input('deviceId'), 'deviceId')]
        ];
    }

    public function deviceData()
    {
        return $this->only(array_keys($this->rules()));
    }

    public function messages()
    {
        return [
            'deviceId.unique' => 'Device id is already registered with other device.',
            'activationCode.unique' => 'Activation code is already registered with other device.',
        ];
    }
}
