<?php

namespace Rector\Tests\Unambiguous\Rector\Expression\FluentSettersToStandaloneCallMethodRector\Fixture;

use Rector\Tests\Unambiguous\Rector\Expression\FluentSettersToStandaloneCallMethodRector\Source\SetNameCaller;

class SkipReturnDifferentObject
{
     public function setup()
     {
          return (new SetNameCaller())
               ->setName('John')
               ->setSurname('Doe');
     }
}
