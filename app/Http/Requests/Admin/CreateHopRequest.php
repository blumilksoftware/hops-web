<?php

declare(strict_types=1);

namespace HopsWeb\Http\Requests\Admin;

use HopsWeb\Models\Hop;

class CreateHopRequest extends HopRequest
{
    public function authorize(): bool
    {
        return $this->user()->can("store", Hop::class);
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            "name" => ["required", "string", "max:255", "unique:hops,name"],
            "slug" => ["required", "string", "max:255", "unique:hops,slug"],
        ]);
    }
}
