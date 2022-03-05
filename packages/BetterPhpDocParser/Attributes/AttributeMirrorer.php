<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\Attributes;

use PHPStan\PhpDocParser\Ast\Node;
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\BetterPhpDocParser\ValueObject\PhpDocAttributeKey;
use Rector\BetterPhpDocParser\ValueObject\StartAndEnd;

final class AttributeMirrorer
{
    /**
     * @var string[]
     */
    private const ATTRIBUTES_TO_MIRROR = [
        PhpDocAttributeKey::PARENT,
        PhpDocAttributeKey::START_AND_END,
        PhpDocAttributeKey::ORIG_NODE,
    ];

    public function mirror(Node $oldNode, Node $newNode): void
    {
        if ($newNode->value instanceof DoctrineAnnotationTagValueNode) {
            $startAndAnd = $oldNode->getAttribute(PhpDocAttributeKey::START_AND_END);
            if ($startAndAnd instanceof StartAndEnd) {
                $end = $startAndAnd->getEnd();
                $lengthIdentifier = strlen((string) $newNode->value->identifierTypeNode);

                if ($end === $lengthIdentifier && $newNode->value->getSilentValue() !== null) {
                    return;
                }
            }
        }

        foreach (self::ATTRIBUTES_TO_MIRROR as $attributeToMirror) {
            if (! $oldNode->hasAttribute($attributeToMirror)) {
                continue;
            }

            $attributeValue = $oldNode->getAttribute($attributeToMirror);
            $newNode->setAttribute($attributeToMirror, $attributeValue);
        }
    }
}
