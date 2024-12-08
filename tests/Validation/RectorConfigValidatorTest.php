<?php

declare(strict_types=1);

namespace Rector\Tests\Validation;

use PHPUnit\Framework\TestCase;
use Rector\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector;
use Rector\Exception\ShouldNotHappenException;
use Rector\Skipper\Skipper\Custom\ReflectionClassSkipperInterface;
use Rector\Skipper\Skipper\CustomSkipperSerializeWrapper;
use Rector\Validation\RectorConfigValidator;
use ReflectionClass;

final class RectorConfigValidatorTest extends TestCase
{
    public function testEnsureRectorRulesExist(): void
    {
        $existingRector = RemoveUnusedPrivatePropertyRector::class;
        $skipper = new class() implements ReflectionClassSkipperInterface {
            public const IMPLEMENTATION_HASH = 'foobiba';

            public function skip(ReflectionClass $reflectionClass): bool
            {
                return true;
            }
        };
        $skip = [
            $existingRector => ['foo/bar', $skipper],
        ];
        RectorConfigValidator::ensureRectorRulesExist($skip);

        $this->assertCount(2, $skip[$existingRector]);
        $this->assertSame('foo/bar', $skip[$existingRector][0]);
        $wrapper = $skip[$existingRector][1];
        $this->assertInstanceOf(CustomSkipperSerializeWrapper::class, $wrapper);
        $this->assertSame($skipper, $wrapper->customSkipper);
        $this->assertSame(
            'O:52:"Rector\Skipper\Skipper\CustomSkipperSerializeWrapper":1:{i:0;s:7:"foobiba";}',
            serialize($wrapper)
        );
    }

    public function testEnsureRectorRulesExistInvalidValue(): void
    {
        $existingRector = RemoveUnusedPrivatePropertyRector::class;
        $skip = [
            $existingRector => 'this is not an array',
        ];
        $this->expectException(ShouldNotHappenException::class);
        $this->expectExceptionMessage(
            'Rule value from "$rectorConfig->skip()" is neither null nor array: ' . $existingRector
        );
        RectorConfigValidator::ensureRectorRulesExist($skip);
    }

    public function testEnsureRectorRulesExistInvalidValueItem(): void
    {
        $existingRector = RemoveUnusedPrivatePropertyRector::class;
        $skip = [
            $existingRector => ['foo/bar', []],
        ];
        $this->expectException(ShouldNotHappenException::class);
        $this->expectExceptionMessage(
            'Rule value from "$rectorConfig->skip()" is neither string nor a supported custom skipper implementation: ' . ($existingRector . '[1]')
        );
        RectorConfigValidator::ensureRectorRulesExist($skip);
    }
}
