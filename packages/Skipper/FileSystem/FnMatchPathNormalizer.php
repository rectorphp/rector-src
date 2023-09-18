<?php

declare(strict_types=1);

namespace Rector\Skipper\FileSystem;

/**
 * @see \Rector\Tests\Skipper\FileSystem\FnMatchPathNormalizerTest
 */
final class FnMatchPathNormalizer
{
    public function normalizeForFnmatch(string $path): string
    {
        if (str_ends_with($path, '*') || str_starts_with($path, '*')) {
            return '*' . trim($path, '*') . '*';
        }

        if (\str_contains($path, '..')) {
            /** @var string|false $path */
            $path = realpath($path);
            if ($path === false) {
                return '';
            }
        }

        return $path;
    }
}
