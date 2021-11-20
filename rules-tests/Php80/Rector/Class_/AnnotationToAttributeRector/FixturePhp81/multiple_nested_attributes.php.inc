<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\FixturePhp81;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81 as Assert;

final class MultipleNestedAttribute
{
    /**
     * @Assert\All({
     *     @Assert\NotNull(),
     *     @Assert\NotNumber(),
     * })
     */
    public $value;
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\FixturePhp81;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81 as Assert;

final class MultipleNestedAttribute
{
    #[\Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81\All(nestedAsserts: [new \Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81\NotNull(), new \Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81\NotNumber()])]
    public $value;
}

?>
