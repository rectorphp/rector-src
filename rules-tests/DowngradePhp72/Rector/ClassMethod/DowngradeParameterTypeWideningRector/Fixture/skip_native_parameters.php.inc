<?php

namespace Rector\Tests\DowngradePhp72\Rector\ClassMethod\DowngradeParameterTypeWideningRector\Fixture;

class SkipNativeParameters implements \SplObserver
{
    public function update(\SplSubject $subject): void
    {
    }
}
