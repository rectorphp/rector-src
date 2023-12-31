<?php

namespace Rector\Tests\Issues\IssueDoubleNestedAnnotatoinDocBlock\Fixture;

use Rector\Tests\Issues\IssueDoubleNestedAnnotatoinDocBlock\Source\SomeAnnotation;

/**
 * @SomeAnnotation(
 *     @SomeAnnotation(key="value")
 * )
 */
final class SomeFixture
{
}
