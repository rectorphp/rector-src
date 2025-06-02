<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source;

/**
 * Single class for all the examples, as class name is often not the tested part
 * @annotation
 */
#[\Attribute]
final class GenericAnnotation
{
    public function __construct($some = null, $itemOperations = null, $collectionOperations = null, $graphql = null, $summary = null, $title = null, $route = null, $choices = [])
    {
    }
}
