<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

final class SkipStatic
{
    /**
     * @var int
     */
    private static $contentFinder;

    public function __construct(int $contentFinder)
    {
        self::$contentFinder = $contentFinder;
    }
}
