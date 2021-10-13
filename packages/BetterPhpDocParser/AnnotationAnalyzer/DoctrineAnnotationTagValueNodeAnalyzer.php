<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\AnnotationAnalyzer;

use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\BetterPhpDocParser\ValueObject\PhpDoc\DoctrineAnnotation\CurlyListNode;
use Rector\Php80\ValueObject\AnnotationToAttribute;

final class DoctrineAnnotationTagValueNodeAnalyzer
{
    /**
     * @param AnnotationToAttribute[] $annotationToAttributes
     */
    public function isNested(
        DoctrineAnnotationTagValueNode $doctrineAnnotationTagValueNode,
        array $annotationToAttributes
    ): bool {
        $values = $doctrineAnnotationTagValueNode->getValues();
        $values = array_filter($values, function ($v, $k) {
            return $v instanceof CurlyListNode;
        }, ARRAY_FILTER_USE_BOTH);

        foreach ($values as $value) {
            $originalValues = $value->getOriginalValues();

            foreach ($originalValues as $originalValue) {
                foreach ($annotationToAttributes as $annotationToAttribute) {
                    // early mark as not nested to avoid false positive
                    if (! $originalValue instanceof DoctrineAnnotationTagValueNode) {
                        return false;
                    }

                    if (! $originalValue->hasClassName($annotationToAttribute->getTag())) {
                        continue;
                    }

                    return true;
                }
            }
        }

        return false;
    }
}
