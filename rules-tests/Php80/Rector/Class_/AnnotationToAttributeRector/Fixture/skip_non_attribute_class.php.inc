<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\NotAnAttribute;

final class SkipNonAttributeClass
{
    /**
    * @NotAnAttribute
    */
    private $foo;
}
