<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\ValueObject\Type;

use PHPStan\PhpDocParser\Ast\Type\ArrayTypeNode;
use PHPStan\PhpDocParser\Ast\Type\CallableTypeNode;
use Stringable;

final class SpacingAwareArrayTypeNode extends ArrayTypeNode implements Stringable
{
    public function __toString(): string
    {
        if ($this->type instanceof CallableTypeNode) {
            return sprintf('(%s)[]', (string) $this->type);
        }

        if ($this->type instanceof BracketsAwareUnionTypeNode && ! $this->type->isWrappedInBrackets()) {
            return sprintf('(%s)[]', (string) $this->type);
        }

        return $this->type . '[]';
    }
}
