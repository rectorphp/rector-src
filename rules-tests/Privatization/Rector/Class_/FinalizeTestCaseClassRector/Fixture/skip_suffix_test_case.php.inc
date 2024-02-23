<?php

namespace Rector\Tests\Privatization\Rector\Class_\FinalizeTestCaseClassRector\Fixture;

use PHPUnit\Framework\TestCase;

class SkipSuffixTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        echo 'init';
    }
}

?>