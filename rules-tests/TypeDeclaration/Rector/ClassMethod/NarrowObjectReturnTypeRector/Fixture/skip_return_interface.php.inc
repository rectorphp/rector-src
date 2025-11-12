<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class SkipReturnInterface
{
    protected function build(): LoggerInterface
    {
        return new NullLogger();
    }
}
