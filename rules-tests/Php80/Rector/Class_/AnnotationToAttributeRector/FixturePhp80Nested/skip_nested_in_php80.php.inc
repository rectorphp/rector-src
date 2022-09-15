<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\FixturePhp81;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81 as Assert;

final class SkipNestedInPhp80
{
    /**
     * @Assert\All({
     *     @Assert\Uuid()
     * })
     */
    public array $selected = [];
}
