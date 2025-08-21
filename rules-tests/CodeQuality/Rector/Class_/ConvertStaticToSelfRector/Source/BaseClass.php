<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\ConvertStaticToSelfRector\Source;

class BaseClass
{
    protected const SUCCESS = 'success';

    protected static string $parentProperty = 'parent value';

    protected static function parentMethod(): string
    {
        return 'parent method';
    }
}
