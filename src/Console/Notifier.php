<?php

declare(strict_types=1);

namespace Rector\Console;

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

final class Notifier
{
    public static function notifyNotSuitableMethodForPHP74(string $calledMethod, string $recommendedMethod): void
    {
        if (PHP_VERSION_ID >= 80000) {
            return;
        }

        $message = sprintf(
            'The "%s()" method uses named arguments. Its suitable for PHP 8.0+. In lower PHP versions, use "%s()" method instead',
            $calledMethod,
            $recommendedMethod
        );

        $symfonyStyle = new SymfonyStyle(new ArgvInput(), new ConsoleOutput());
        $symfonyStyle->warning($message);

        sleep(3);
    }

    public static function notifyNotSuitableMethodForPHP80(string $calledMethod, string $recommendedMethod): void
    {
        // current project version check
        if (PHP_VERSION_ID < 80000) {
            return;
        }

        $message = sprintf(
            'The "%s()" method is suitable for PHP 7.4 and lower. Use "%s()" method instead.',
            $calledMethod,
            $recommendedMethod
        );

        $symfonyStyle = new SymfonyStyle(new ArgvInput(), new ConsoleOutput());
        $symfonyStyle->warning($message);

        sleep(3);
    }
}
