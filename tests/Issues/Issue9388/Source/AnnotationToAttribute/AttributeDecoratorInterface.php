<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\Issue9388\Source\AnnotationToAttribute;

use PhpParser\Node\Attribute;

interface AttributeDecoratorInterface
{
    public function supports(string $phpAttributeName): bool;

    public function decorate(Attribute $attribute): void;
}
