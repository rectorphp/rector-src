<?php

namespace Rector\Tests\Issues\IssueDoubleNestedAnnotationDocBlock\Fixture;

use Rector\Tests\Issues\IssueDoubleNestedAnnotationDocBlock\Source\SomeAnnotation;

/**
 * @SomeAnnotation(
 *     @SomeAnnotation(key="value")
 * )
 */
final class SomeFixture
{
}
