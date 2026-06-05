<?php

namespace Rector\RectorCompatTests\Tests\Rector\MakeClassFinalRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class MakeClassFinalRectorTest extends AbstractRectorTestCase
{
    public function test(): void
    {
        $this->doTestFile(__DIR__ . '/Fixture/some_non_final_class.php.inc');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
