<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\DataProviderArrayItemsNewlinedRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipTestMethod extends TestCase
{
    public function test(): array
    {
        return [['content', 8], ['content123', 11]];
    }
}
