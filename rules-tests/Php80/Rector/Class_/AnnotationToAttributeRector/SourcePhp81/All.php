<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81;

#[\Attribute]
final class All
{
    public function __construct(array $nestedAsserts)
    {
    }
}
