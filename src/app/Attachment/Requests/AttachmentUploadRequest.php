<?php

namespace App\Attachment\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttachmentUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [

            'file' => [

                'required',

                'file',

                sprintf(
                    'max:%s',
                    config('attachment.max_size')
                ),

                sprintf(
                    'mimes:%s',
                    implode(
                        ',',
                        config('attachment.allowed_extensions')
                    )
                ),

            ],

        ];
    }

    /**
     * Validation messages.
     */
    public function messages(): array
    {
        return [

            'file.required' => 'Attachment file is required.',

            'file.file' => 'The uploaded file is invalid.',

            'file.max' => sprintf(

                'The attachment may not be greater than %s KB.',

                config('attachment.max_size')

            ),

            'file.mimes' => sprintf(

                'The attachment must be one of the following types: %s.',

                implode(
                    ', ',
                    config('attachment.allowed_extensions')
                )

            ),

        ];
    }

    /**
     * Validation attributes.
     */
    public function attributes(): array
    {
        return [

            'file' => 'attachment',

        ];
    }
}