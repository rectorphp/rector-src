<?php

declare(strict_types=1);

namespace Rector\PhpAttribute;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;

final class AttributeArrayNameInliner
{
    /**
     * @return Arg[]
     */
    public function inlineArrayToArgs(Array_ $array): array
    {
        $args = [];

        foreach ($array->items as $arrayItem) {
            if (! $arrayItem instanceof ArrayItem) {
                continue;
            }

            $key = $arrayItem->key;
            if ($key instanceof String_) {
                $args[] = new Arg($arrayItem->value, false, false, [], new Identifier($key->value));
            } else {
                $args[] = new Arg($arrayItem->value);
            }
        }

        return $args;
    }
}
