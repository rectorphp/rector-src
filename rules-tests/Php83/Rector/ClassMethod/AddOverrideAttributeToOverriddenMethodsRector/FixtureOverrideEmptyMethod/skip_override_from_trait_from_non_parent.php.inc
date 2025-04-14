<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\FixtureOverrideEmptyMethod;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromTrait;

// @see https://3v4l.org/ACu8F
final class SkipOverrideFromTraitFromNonParent
{
    use ExampleFromTrait;

    public function run()
    {
        return 'hello';
    }
}
