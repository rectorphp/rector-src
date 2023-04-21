<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\PhpDoc;

use PhpParser\Node\Scalar\String_;
use PHPStan\PhpDocParser\Ast\NodeAttributes;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use Stringable;

final class ArrayItemNode implements PhpDocTagValueNode, Stringable
{
    use NodeAttributes;

    /**
     * @deprecated
     */
    public int|null $kindValueQuoted = String_::KIND_DOUBLE_QUOTED;

    /**
     * @deprecated
     */
    public int|null $kindKeyQuoted = String_::KIND_DOUBLE_QUOTED;

    public function __construct(
        public mixed $value,
        public mixed $key = null,
    ) {
    }

    public function __toString(): string
    {
        $value = '';

        if ($this->key !== null) {
            $value .= $this->key . '=';
        }

        if (is_array($this->value)) {
            foreach ($this->value as $singleValue) {
                $value .= $singleValue;
            }
        } else {
            $value .= $this->value;
        }

        return $value;
    }
}
