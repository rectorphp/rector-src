<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\PhpDoc;

use PHPStan\PhpDocParser\Ast\NodeAttributes;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use Stringable;

final class ArrayItemNode implements PhpDocTagValueNode, Stringable
{
    use NodeAttributes;

    public function __construct(
        public mixed $value,
        public mixed $key = null,
    ) {
    }

    public function __toString(): string
    {
        $value = '';

        if ($this->key !== null && ! is_int($this->key)) {
            $value .= $this->key . '=';
        }

        if (is_array($this->value)) {
            foreach ($this->value as $singleValue) {
                $value .= $singleValue;
            }
        } elseif ($this->value instanceof DoctrineAnnotationTagValueNode) {
            $value .= '@' . ltrim((string) $this->value->identifierTypeNode, '@') . $this->value;
        } else {
            $value .= $this->value;
        }

        return $value;
    }
}
