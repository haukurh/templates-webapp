<?php

/**
 * Require a view.
 *
 * @param string $name
 * @param array $data
 * @return mixed
 */
function view(string $viewName, array $data = [])
{
    extract($data, EXTR_OVERWRITE);

    return require __DIR__ . "/views/{$viewName}.view.php";
}

/**
 * Redirect to a new page.
 *
 * @param string $path
 */
function redirect(string $path)
{
    header("Location: /{$path}");
}

/**
 * @throws JsonException
 */
function json(array $payload, int $code = 200)
{
    http_response_code($code);
    header('Content-Type: application/json');
    die(json_encode($payload, JSON_PRETTY_PRINT|JSON_THROW_ON_ERROR));
}

/**
 * Returns UUIDv4
 *
 * @return string
 * @throws Exception
 */
function uuid4(): string
{
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        random_int( 0, 0xffff ), random_int( 0, 0xffff ),

        // 16 bits for "time_mid"
        random_int( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        random_int( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        random_int( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        random_int( 0, 0xffff ), random_int( 0, 0xffff ), random_int( 0, 0xffff )
    );
}
