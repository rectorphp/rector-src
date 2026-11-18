<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class SkipFirstClassCallable
{
    /**
     * @throws \RuntimeException
     */
    public function fail(): void
    {
        throw new \RuntimeException('boom');
    }

    public function callable(): \Closure
    {
        return $this->fail(...);
    }

    public static function staticCallable(): \Closure
    {
        return self::create(...);
    }

    /**
     * @throws \RuntimeException
     */
    public static function create(): self
    {
        throw new \RuntimeException('boom');
    }
}
