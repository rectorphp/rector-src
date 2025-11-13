<?php

namespace Rector\Tests\Unambiguous\Rector\Expression\FluentSettersToStandaloneCallMethodRector\Fixture;

use Rector\Tests\Unambiguous\Rector\Expression\FluentSettersToStandaloneCallMethodRector\Source\SomeSetterClass;

final class SkipSoleSetter
{
    public function setup()
    {
        return (new SomeSetterClass())
            ->setName('John');
    }
}
