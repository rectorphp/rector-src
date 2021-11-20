<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\FixturePhp81;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81 as Assert;

final class NestedAttributesWithArgument
{
    /**
     * @Assert\All({
     *     @Assert\NotNumber(1000)
     * })
     */
    public $value;
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\FixturePhp81;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81 as Assert;

final class NestedAttributesWithArgument
{
    #[\Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81\All(nestedAsserts: [new \Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81\NotNumber(1000)])]
    public $value;
}

?>
