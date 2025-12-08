<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

use Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Source\WithPrivateMethod;

final class SkipPrivateMethodByInstantiation
{
    public function run()
    {
      $thing = new WithPrivateMethod();
      $thing->setHandler([$thing, 'defaultHandler']);
      $thing->doTheThing();
    }
}
