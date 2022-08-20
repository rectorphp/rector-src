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
     * @param String_::KIND_*|null $kindValueQuoted
     */
    public function __construct(
        public mixed $value,
        public mixed $key,
        public int|null $kindValueQuoted = null,
        public int|null $kindKeyQuoted = null,
    ) {
    }

    public function __toString(): string
    {
        $value = '';

        if ($this->kindKeyQuoted === String_::KIND_DOUBLE_QUOTED) {
            $value .= '"' . $this->key . '" = ';
        } elseif ($this->key !== null) {
            $value .= $this->key . '=';
        }

        // @todo depends on the context! possibly the top array is quting this stinrg already
        if ($this->kindValueQuoted === String_::KIND_DOUBLE_QUOTED) {
            $value .= '"' . $this->value . '"';
        } elseif (is_array($this->value)) {
            foreach ($this->value as $singleValue) {
                $value .= $singleValue;
            }
        } else {
            $value .= $this->value;
        }

        return $value;
    }
}
