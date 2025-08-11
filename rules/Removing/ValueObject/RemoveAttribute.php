<?php

declare(strict_types=1);

namespace Rector\Removing\ValueObject;

use PhpParser\Node;
use Rector\Validation\RectorAssert;

final readonly class RemoveAttribute
{
    /**
     * @param list<class-string<Node>> $nodeTypes
     */
    public function __construct(
        private string $class,
        private array $nodeTypes = [],
    ) {
        RectorAssert::className($class);
        foreach ($nodeTypes as $nodeType) {
            RectorAssert::className($nodeType);
        }
    }

    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return list<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return $this->nodeTypes;
    }
}
