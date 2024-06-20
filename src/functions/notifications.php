<?php

declare(strict_types=1);

// @use in config builder
function reportMethodUsedAbovePHP80(string $calledMethod, string $recommendedMethod): void
{
    // current project version check
    if (PHP_VERSION_ID < 80000) {
        return;
    }

    echo sprintf(
        'The "%s()" method is suitable for PHP 7.4 and lower. Use "%s()" method instead.',
        $calledMethod,
        $recommendedMethod
    );
    sleep(3);
}
