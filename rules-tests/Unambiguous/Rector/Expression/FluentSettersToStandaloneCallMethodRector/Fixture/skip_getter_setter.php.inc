<?php

declare(strict_types=1);

namespace Rector\Tests\Unambiguous\Rector\Expression\FluentSettersToStandaloneCallMethodRector\Fixture;

use Rector\Tests\Unambiguous\Rector\Expression\FluentSettersToStandaloneCallMethodRector\Source\GetterSetterClass;

final class SkipGetterSetter
{
    public function setup()
    {
        return (new GetterSetterClass())
            ->getter()
            ->setName('John')
            ->setSurname('Doe');
    }
}
