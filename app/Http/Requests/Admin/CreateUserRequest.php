<?php

declare(strict_types=1);

namespace HopsWeb\Http\Requests\Admin;

use HopsWeb\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can("store", User::class);
    }

    public function rules(): array
    {
        return [
            "name" => ["string", "max:255"],
            "email" => [
                "string",
                "email",
                "max:255",
                Rule::unique("users"),
            ],
            "password" => ["required", "string", "min:8"],
            "is_admin" => ["boolean"],
            "is_team_member" => ["boolean"],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            "is_admin" => $this->boolean("is_admin"),
            "is_team_member" => $this->boolean("is_team_member"),
        ]);
    }
}
