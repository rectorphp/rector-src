<?php

namespace GlobalInstall;

use CodeIgniter\Exceptions\ExceptionInterface;

class MyException extends RuntimeException implements ExceptionInterface
{
    public static function forAnything(string $content)
    {
        return new static($content);
    }
}