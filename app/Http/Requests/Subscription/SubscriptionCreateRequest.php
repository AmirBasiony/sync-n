<?php

namespace App\Http\Requests\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'string', 'unique:mongodb.subscriptions'],
            'package_id' => ['required', 'integer'],
        ];
    }

    public function messages()
    {
        return [
            'user_id.unique' => 'User already subscribed to a plan',
        ];
    }
}
