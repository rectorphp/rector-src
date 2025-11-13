<?php

namespace Rector\Tests\Unambiguous\Rector\Expression\FluentSettersToStandaloneCallMethodRector\Source;

final class GetterSetterClass
{
    public function getter()
    {
        return new SomeSetterClass();
    }
}
