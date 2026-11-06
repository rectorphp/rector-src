<?php

declare(strict_types=1);

namespace Rector\Bootstrap;

/**
 * Tiny argv option reader, used during bootstrap before the container (and the console) exist.
 */
final class CliOptionValueResolver
{
    /**
     * Resolve the value of "--name value", "--name=value", "-c value" or "-c=value" from raw argv.
     *
     * @param array<int, mixed> $argv
     * @param string[] $optionNames fully dashed, e.g. ['--config', '-c']
     */
    public static function resolve(array $argv, array $optionNames): ?string
    {
        foreach ($argv as $index => $arg) {
            if (! is_string($arg)) {
                continue;
            }

            foreach ($optionNames as $optionName) {
                if ($arg === $optionName) {
                    $nextArg = $argv[$index + 1] ?? null;
                    return is_string($nextArg) ? $nextArg : null;
                }

                if (str_starts_with($arg, $optionName . '=')) {
                    return substr($arg, strlen($optionName) + 1);
                }
            }
        }

        return null;
    }
}
