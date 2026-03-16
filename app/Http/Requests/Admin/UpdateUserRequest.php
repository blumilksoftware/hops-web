<?php

declare(strict_types=1);

namespace HopsWeb\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can("update", $this->route("user"));
    }

    public function rules(): array
    {
        return [
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
