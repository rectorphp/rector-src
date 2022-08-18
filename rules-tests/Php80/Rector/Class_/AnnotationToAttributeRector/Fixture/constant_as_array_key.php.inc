<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation;
use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\ConstantReference;

/**
 * @GenericAnnotation({
 *     ConstantReference::FIRST_NAME = true,
 *     "some::string" = "some-value"
 * })
 */
final class ConstantAsArrayKey
{
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation;
use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\ConstantReference;

#[GenericAnnotation([ConstantReference::FIRST_NAME => true, 'some::string' => 'some-value'])]
final class ConstantAsArrayKey
{
}

?>
