<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\FixturePhp81;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81 as Assert;

final class VariousArguments
{
    /**
     * @Assert\All({
     *     @Assert\Length(1000),
     *     @Assert\NotNumber(hey=10, hi="hello")
     * })
     */
    public $value;
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\FixturePhp81;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81 as Assert;

final class VariousArguments
{
    #[\Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81\All(nestedAsserts: [new \Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81\Length(1000), new \Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81\NotNumber(hey: 10, hi: 'hello')])]
    public $value;
}

?>
