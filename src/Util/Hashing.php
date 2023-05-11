<?php

declare(strict_types=1);

namespace Rector\Core\Util;

final class Hashing
{
    public function hash(string $data): string
    {
        // md4 is faster then md5 https://php.watch/articles/php-hash-benchmark
        $hashingAlgorithm = 'md4';
        if (\PHP_VERSION_ID >= 80100) {
            // if xxh128 is available use it, as it is way faster then md4 https://php.watch/articles/php-hash-benchmark
            $hashingAlgorithm = 'xxh128';
        }

        return hash($hashingAlgorithm, $data);
    }
}
