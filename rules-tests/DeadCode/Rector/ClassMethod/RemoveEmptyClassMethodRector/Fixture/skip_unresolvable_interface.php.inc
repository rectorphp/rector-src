<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

final class SkipUnresolvableInterface implements \App\OutOfScope\PostingInterface
{
    public function submitPosting(): void
    {
    }
}
