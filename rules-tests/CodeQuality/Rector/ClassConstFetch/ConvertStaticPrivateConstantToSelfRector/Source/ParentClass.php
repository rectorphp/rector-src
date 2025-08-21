<?php

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\ConvertStaticPrivateConstantToSelfRector\Source;

class ParentClass
{
    public const FAILURE = 1;
    protected const SUCCESS = 0;
}
