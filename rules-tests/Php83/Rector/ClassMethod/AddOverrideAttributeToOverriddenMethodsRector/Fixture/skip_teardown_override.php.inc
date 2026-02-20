<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\SomeAbstractTestCase;

final class SkipTearDownOverrideTest extends SomeAbstractTestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        $result = null;
    }
}
