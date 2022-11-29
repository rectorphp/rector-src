<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictSetUpRector\Fixture;

final class SkipNoTestCase
{
    private $value;

    public function setUp()
    {
        $this->value = 1000;
    }
}
