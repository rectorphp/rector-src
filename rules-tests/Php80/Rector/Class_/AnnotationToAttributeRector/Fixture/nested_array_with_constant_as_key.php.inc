<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation;
use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\ConstantReference;

/**
 * @GenericAnnotation(
 *     some={
 *          ConstantReference::FIRST_NAME={
 *              foo: {bar:{"baz"}}
 *          },
 *          ConstantReference::LAST_NAME
 *     }
 * )
 */
final class ArrayWithConstantAsKey
{
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation;
use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\ConstantReference;

#[GenericAnnotation(some: [ConstantReference::FIRST_NAME => ['foo' => ['bar' => ['baz']]], ConstantReference::LAST_NAME])]
final class ArrayWithConstantAsKey
{
}

?>
