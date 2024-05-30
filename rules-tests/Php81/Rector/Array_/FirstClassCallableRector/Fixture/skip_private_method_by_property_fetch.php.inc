<?php

namespace Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Fixture;

use Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Source\WithPrivateMethod;

final class SkipPrivateMethodByPropertyFetch
{
  public function __construct(protected $thing = new WithPrivateMethod())
  {
  }

  public function run()
  {
    $this->thing->setHandler([$this->thing, 'defaultHandler']);
    $this->thing->doTheThing();
  }
}
