<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\FixturePhp81;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81 as Assert;

final class NestedAttribute
{
    /**
     * @Assert\All({
     *     @Assert\NotNull,
     *     @Assert\Length(max=6)
     * })
     */
    public $value;
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\FixturePhp81;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81 as Assert;

final class NestedAttribute
{
    #[Assert\All([new Assert\NotNull, new Assert\Length(max: 6)])]
    public $value;
}

?>
