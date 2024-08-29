<?php

namespace App\Traits;

trait JsonSerializer
{
    public function jsonSerialize(): array
    {
        $result = array();

        foreach (get_object_vars($this) as $key => $data) {
            if (!is_object($data)) {
                $result[$key] = $data;
            }
        }

        return $result;
    }
}
