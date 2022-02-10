<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

use PHPUnit\Framework\MockObject\MockObject;

class BadMixedCodeComment
{
  /** @var Foo|mixed|MockObject */
  private $x;
}