<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\DataProviderArrayItemsNewlinedRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipNoArray extends TestCase
{
    public function provideData(): array
    {
        return 'content' . 8;
    }
}
