<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\Issue9388\AnnotationToAttribute;

use PhpParser\Node\Attribute;

final readonly class AttributeDecorator
{
    /**
     * @param AttributeDecoratorInterface[] $decorators
     */
    public function __construct(private array $decorators)
    {
    }

    public function decorate(string $phpAttributeName, Attribute $attribute): void
    {
        foreach ($this->decorators as $decorator) {
            if ($decorator->supports($phpAttributeName)) {
                $decorator->decorate($attribute);
            }
        }
    }
}
