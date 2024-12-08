<?php

declare(strict_types=1);

namespace Rector\Tests\Skipper\Skipper;

use Nette\Utils\FileSystem;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class CustomSkipperTest extends AbstractRectorTestCase
{
    public function testSkipClassWithSpecificAttributeOnly(): void
    {
        // Cannot use `$this->doTestFile()` because ReflectionClass needs an actual existing loadable class
        $originalFilePath = __DIR__ . '/Fixture/CustomSkipper/ClassWithUnusedProperty.php';
        $this->inputFilePath = $originalFilePath . '.source.inc';
        $expectedFilePath = $originalFilePath . '.expected.inc';
        FileSystem::copy($originalFilePath, $this->inputFilePath);
        SimpleParameterProvider::setParameter(Option::SOURCE, [$this->inputFilePath]);
        $this->processFilePath($this->inputFilePath);
        $this->assertFileEquals($expectedFilePath, $this->inputFilePath);
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/custom_skipper_rule.php';
    }
}
