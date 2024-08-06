<?php

declare(strict_types=1);

namespace Rector\PhpAttribute\NodeFactory;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\PhpAttribute\Enum\AnnotationClassName;

final class AnnotationToAttributeKeyTypeCaster
{
    /**
     * @var array<string, string[]>
     */
    private const INTEGER_KEYS_BY_CLASS = [
        AnnotationClassName::LENGTH => ['min', 'max'],
    ];

    /**
     * @param Arg[] $args
     */
    public function castAttributeTypes(AnnotationToAttribute $annotationToAttribute, array $args): void
    {
        // known type casting
        foreach (self::INTEGER_KEYS_BY_CLASS as $annotationClass => $integerKeys) {
            if ($annotationToAttribute->getAttributeClass() !== $annotationClass) {
                continue;
            }

            foreach ($integerKeys as $integerKey) {
                foreach ($args as $arg) {
                    if (! $arg->value instanceof ArrayItem) {
                        continue;
                    }

                    $arrayItem = $arg->value;
                    if (! $arrayItem->key instanceof String_) {
                        continue;
                    }

                    if ($arrayItem->key->value !== $integerKey) {
                        continue;
                    }

                    // ensure type is casted to integer
                    if ($arrayItem->value instanceof String_) {
                        $arrayItem->value = new LNumber((int) $arrayItem->value->value);
                    }
                }
            }
        }
    }
}
