<?php

declare(strict_types=1);

namespace Monolog;

if (class_exists('Monolog\Logger')) {
    return;
}

class Logger
{
    public const API = 3;
}