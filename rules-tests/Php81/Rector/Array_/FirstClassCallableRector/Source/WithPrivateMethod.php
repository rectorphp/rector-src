<?php

declare(strict_types=1);

namespace Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Source;

class WithPrivateMethod {
  /** @var callable */
  protected $handler;

  public function setHandler(?callable $handler): void {
    $this->handler = $handler;
  }

  public function doTheThing(): void {
    ($this->handler)();
  }

  private function defaultHandler(): void {
    echo 'default handler';
  }
}
