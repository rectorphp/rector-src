<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81;

#[\Attribute]
final class NotNumber
{
    public function __construct($firstValue = null, $secondValue = null, $hey = null, $hi = null)
    {
    }
}
