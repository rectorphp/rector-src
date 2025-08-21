<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\StaticToSelfStaticMethodCallOnFinalClassRector\Source;

class BaseClass
{
    protected static function parentMethod(): string
    {
        return 'parent method';
    }
}
