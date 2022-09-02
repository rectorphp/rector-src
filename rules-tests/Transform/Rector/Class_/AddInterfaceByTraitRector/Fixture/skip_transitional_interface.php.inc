<?php

namespace Rector\Tests\Transform\Rector\Class_\AddInterfaceByTraitRector\Fixture;

use Rector\Tests\Transform\Rector\Class_\AddInterfaceByTraitRector\Source\AnotherTrait;
use Rector\Tests\Transform\Rector\Class_\AddInterfaceByTraitRector\Source\MiddleInterface;

final class SkipTransitionalInterface implements MiddleInterface
{
    use AnotherTrait;
}
