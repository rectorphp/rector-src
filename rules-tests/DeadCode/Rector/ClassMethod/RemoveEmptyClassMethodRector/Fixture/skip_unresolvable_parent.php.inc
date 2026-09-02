<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

final class SkipUnresolvableParent extends \App\OutOfScope\AbstractPosting
{
    protected function submitPosting(): void
    {
    }
}
