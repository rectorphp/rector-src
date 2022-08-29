<?php

declare(strict_types=1);

namespace Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\Source;

abstract class ClassWithFileSystemMethod
{
    public function getSmartFileSystem(): TargetFileSystem
    {
        return new TargetFileSystem();
    }
}
