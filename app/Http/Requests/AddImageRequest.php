<?php

namespace App\Http\Requests;

use App\Enums\ImageAction;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class AddImageRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'image' => [
                'required',
                File::types(['jpg', 'jpeg', 'png'])
                    ->max(10 * 1024 * 1024)
            ],
            'action' => [
                'required',
                Rule::enum(ImageAction::class)
            ],
            'width' => [
                'required_if:action,' . ImageAction::DIMENSIONS->value,
                'nullable',
                'integer',
                'min:1',
                'max:1000'
            ],
            'height' => [
                'required_if:action,' . ImageAction::DIMENSIONS->value,
                'nullable',
                'integer',
                'min:1',
                'max:1000'
            ],
            'watermark' => [
                'required_if:action,' . ImageAction::WATERMARK->value,
                'nullable',
                'string',
                'max:255'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Action is required and must be one of: ' . implode(', ', [
                ImageAction::DIMENSIONS->value,
                ImageAction::WEBP->value,
                ImageAction::WATERMARK->value
            ]),
            'action.enum' => 'Action must be one of: ' . implode(', ', [
                ImageAction::DIMENSIONS->value,
                ImageAction::WEBP->value,
                ImageAction::WATERMARK->value
            ]),
        ];
    }
}
