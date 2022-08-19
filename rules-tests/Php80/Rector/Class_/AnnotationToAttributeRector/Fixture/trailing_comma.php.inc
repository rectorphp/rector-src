<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation;

final class TrailingComma
{
    /**
     * @GenericAnnotation(key="value", )
     * @GenericAnnotation(
     *     "some" = "item",
     *     "summary" = "item",
     * )
     */
    protected $someColumn;
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation;

final class TrailingComma
{
    #[GenericAnnotation(key: 'value')]
    #[GenericAnnotation(some: 'item', summary: 'item')]
    protected $someColumn;
}

?>
