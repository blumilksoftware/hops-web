<?php

declare(strict_types=1);

namespace HopsWeb\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class UpdateHopRequest extends HopRequest
{
    public function authorize(): bool
    {
        return $this->user()->can("update", $this->route("hop"));
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            "name" => ["required", "string", "max:255", Rule::unique("hops", "name")->ignore($this->route("hop"))],
            "slug" => ["required", "string", "max:255", Rule::unique("hops", "slug")->ignore($this->route("hop"))],
        ]);
    }
}
