<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\ValueObject;

use PhpParser\Node\Attribute;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;

final class DataProviderNodes
{
    /**
     * @param array<array-key, Attribute|PhpDocTagNode> $nodes
     */
    public function __construct(
        public readonly array $nodes,
    ) {
    }

    public function isEmpty(): bool
    {
        return $this->nodes === [];
    }
}
