<?php

declare(strict_types=1);

namespace Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class ArgumentAdderRectorTest extends AbstractRectorTestCase
{
    public function provideFixtureDirectory(): string
    {
        return __DIR__ . '/Fixture';
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
