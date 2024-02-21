<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation;

final class WithDescription
{
    /**
     * This comment is before the annotations
     * @GenericAnnotation this is a simple annotation
     * @GenericAnnotation(key="value") this annotation has parameters
     * @GenericAnnotation(
     *     "some" = "item",
     *     "summary" = "item",
     * ) this annotation is multi-line
     * @GenericAnnotation(key="value") (this comment is within parentheses)
     * @GenericAnnotation(key="value") "this comment is within quotes"
     * This comment does not belong to an annotation and will be ignored
     */
    protected $someColumn;
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation;

final class WithDescription
{
    /**
     * This comment is before the annotations
     */
    #[GenericAnnotation] // this is a simple annotation
    #[GenericAnnotation(key: 'value')] // this annotation has parameters
    #[GenericAnnotation(some: 'item', summary: 'item')] // this annotation is multi-line
    #[GenericAnnotation(key: 'value')] // (this comment is within parentheses)
    #[GenericAnnotation(key: 'value')] // "this comment is within quotes"
    protected $someColumn;
}

?>
