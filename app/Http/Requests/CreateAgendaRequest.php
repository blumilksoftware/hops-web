<?php

declare(strict_types=1);

namespace HopsWeb\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAgendaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:255"],
            "query_json" => ["nullable", "string", "json"],
        ];
    }

    public function getData(): array
    {
        return [
            "name" => $this->string("name")->trim()->value(),
            "query_json" => $this->input("query_json"),
        ];
    }
}
