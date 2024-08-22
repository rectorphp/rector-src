<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\EmptyLongArraySyntax\Source;

final class ParentWithEmptyLongArray
{
    public function run($default = array())
    {
    }
}
