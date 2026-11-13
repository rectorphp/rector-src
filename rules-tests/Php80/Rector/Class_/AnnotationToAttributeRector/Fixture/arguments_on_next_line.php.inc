<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation;

/**
 * @GenericAnnotation
 * (
 *   title = "Next line",
 *   summary = "Some summary"
 * )
 */
final class ArgumentsOnNextLine
{
}

/**
 * @\Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation
 * (title = "Fully qualified")
 */
final class FullyQualifiedArgumentsOnNextLine
{
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation;

#[GenericAnnotation(title: 'Next line', summary: 'Some summary')]
final class ArgumentsOnNextLine
{
}

#[\Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation(title: 'Fully qualified')]
final class FullyQualifiedArgumentsOnNextLine
{
}

?>
