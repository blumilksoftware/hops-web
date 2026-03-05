<?php

declare(strict_types=1);

namespace HopsWeb\Helpers;

class Json5Parser
{
    public function parse(string $content): ?array
    {
        $json = preg_replace('/(?<=[{,\n])\s*([a-zA-Z_]\w*)\s*:/m', '"$1":', $content);

        $data = json_decode($json, true);

        return is_array($data) ? $data : null;
    }
}
