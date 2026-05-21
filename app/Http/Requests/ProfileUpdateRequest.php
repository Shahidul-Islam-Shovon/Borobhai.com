<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            // ছবি ভ্যালিডেশন: শুধুমাত্র jpeg, png, jpg, gif ফাইল এবং সর্বোচ্চ ২ মেগাবাইট হতে পারবে
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:6048'],
        ];
    }
}