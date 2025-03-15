<?php

if (! function_exists('json_pretty')) {
    /**
     * @param array|string $value
     * @return string
     */
    function json_pretty(array|string $value): string
    {
        $json = json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return str_replace('\/', '/', trim($json, '"'));
    }
}
