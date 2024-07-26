<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\AutoImport\Source;

class SomeClass
{
    public function __toString()
    {
        return 'test';
    }
}
