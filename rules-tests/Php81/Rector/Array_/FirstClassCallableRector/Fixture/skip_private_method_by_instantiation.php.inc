<?php

namespace Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Fixture;

use Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Source\WithPrivateMethod;

final class SkipPrivateMethodByInstantiation
{
    public function run()
    {
      $thing = new WithPrivateMethod();
      $thing->setHandler([$thing, 'defaultHandler']);
      $thing->doTheThing();
    }
}
