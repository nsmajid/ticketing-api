<?php

namespace App\User\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                Rule::unique('users')
                    ->ignore($userId),
            ],

            'password' => [
                'nullable',
                'min:8',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'company_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'position' => [
                'nullable',
                'string',
                'max:255',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],

            'role' => [
                'required',
                'exists:roles,name',
            ],
        ];
    }
}