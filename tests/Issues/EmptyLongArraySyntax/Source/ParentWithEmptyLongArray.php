<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\EmptyLongArraySyntax\Source;

class ParentWithEmptyLongArray
{
    public function run($default = array())
    {
    }
}