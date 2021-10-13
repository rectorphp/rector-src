<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\PhpDoc\Analyzer;

use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\BetterPhpDocParser\ValueObject\PhpDoc\DoctrineAnnotation\CurlyListNode;

final class DoctrineAnnotationTagValueNodeAnalyzer
{
    public function isNested(
        DoctrineAnnotationTagValueNode $doctrineAnnotationTagValueNode,
        array $annotationToAttributes
    ): bool {
        $values = $doctrineAnnotationTagValueNode->getValues();
        foreach ($values as $value) {
            // early mark as not nested to avoid false positive
            if (! $value instanceof CurlyListNode) {
                return false;
            }

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
