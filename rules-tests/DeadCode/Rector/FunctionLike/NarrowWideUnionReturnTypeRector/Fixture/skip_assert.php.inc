<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Fixture;

final class SkipAssert
{
    /**
     * @var array{
     *     cache: array{directory: null}
     * }
     */
    protected array $config = [
        'cache' => [
            'directory' => null,
        ],
    ];

    public function getCacheDirectory(): ?string
    {
        $dir = $this->config['cache']['directory'] ?? null;
        \assert(\is_string($dir) || $dir === null, 'Invalid cache directory.');

        return $dir;
    }
}

