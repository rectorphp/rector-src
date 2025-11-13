<?php

namespace Rector\Tests\Unambiguous\Rector\Expression\FluentSettersToStandaloneCallMethodRector\Fixture;

use Rector\Tests\Unambiguous\Rector\Expression\FluentSettersToStandaloneCallMethodRector\Source\SomeSetterClass;

final class SomeClass
{
    public function setup()
    {
        return (new SomeSetterClass())
            ->setName('John')
            ->setSurname('Doe');
    }
}

?>
-----
<?php

namespace Rector\Tests\Unambiguous\Rector\Expression\FluentSettersToStandaloneCallMethodRector\Fixture;

use Rector\Tests\Unambiguous\Rector\Expression\FluentSettersToStandaloneCallMethodRector\Source\SomeSetterClass;

final class SomeClass
{
    public function setup()
    {
        $someSetterClass = new SomeSetterClass();
        $someSetterClass->setSurname('Doe');
        $someSetterClass->setName('John');
    }
}

?>
