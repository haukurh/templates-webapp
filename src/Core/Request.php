<?php

namespace Template\Core;

class Request
{
    /**
     * Fetch the request URI.
     *
     * @return string
     */
    public static function uri(): string
    {
        return trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    }

    /**
     * Fetch the request method.
     *
     * @return string
     */
    public static function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
