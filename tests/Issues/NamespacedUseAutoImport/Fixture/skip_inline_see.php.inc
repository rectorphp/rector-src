<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Rector\Tests\Issues\NamespacedUse\Source\SomeClassFirst;
use Rector\Tests\Issues\NamespacedUse\Source\SomeClassSecond;
use Rector\Tests\Issues\NamespacedUse\Source\SomeClassThird;

final class SkipInlineSee
{
    /**
     * See {@see SomeClassFirst} for more information.
     * See {@see SomeClassSecond::test()} for more information.
     * See {@see SomeClassThird::$property} for more information.
     */
    public function test(): void {}
}
