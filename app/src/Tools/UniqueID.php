<?php

namespace App\Tools;

use Exception;

class UniqueID
{

    /**
     * Generates a cryptographically secure random unique ID.
     *
     * @param int $length The length of the generated unique ID. Defaults to 13.
     * @return string The generated unique ID.
     *
     * @throws Exception When no cryptographically secure random function is available.
     */
    public static function uniqueIdReal(int $length = 13): string
    {

        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($length / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
        } else {
            throw new Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $length);
    }

    public static function uniqueIdInt(int $length = 5, int $min = 11111, int $max = 99999): int
    {
        return intval(substr(100000 + ((rand($min, $max)) * 97 + 356563) % 896723, 0, $length));
    }
}