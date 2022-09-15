<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\FixturePhp81;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81 as Assert;

final class SkipNestedInPhp80
{
    public const DEFAULT_ITEMS_FILTER = ProductCriteria::FILTER_ITEMS_ALL;

    /**
     * @Assert\All({
     *     @Assert\Uuid()
     * })
     */
    public array $selected = [];

    #[Assert\Type('string'), Assert\Choice(ProductCriteria::ALLOWED_FILTER_ITEMS)]
    public string $filterItems = self::DEFAULT_ITEMS_FILTER;
}

?>