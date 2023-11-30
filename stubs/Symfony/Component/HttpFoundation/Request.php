<?php

/** @changelog https://github.com/symfony/http-foundation/blob/7.0/Request.php */

declare(strict_types=1);

namespace Symfony\Component\HttpFoundation;

if (class_exists('Symfony\Component\HttpFoundation\Request')) {
    return;
}

class Request
{
    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
    }
}