<?php

declare(strict_types=1);

namespace Rector\PhpAttribute\AnnotationToAttributeMapper;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use Rector\BetterPhpDocParser\PhpDoc\ArrayItemNode;
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\BetterPhpDocParser\PhpDoc\StringNode;
use Rector\PhpAttribute\AnnotationToAttributeMapper;
use Rector\PhpAttribute\Contract\AnnotationToAttributeMapperInterface;
use Rector\PhpAttribute\Enum\DocTagNodeState;
use Rector\Validation\RectorAssert;
use Webmozart\Assert\InvalidArgumentException;

/**
 * @implements AnnotationToAttributeMapperInterface<ArrayItemNode>
 */
final class ArrayItemNodeAnnotationToAttributeMapper implements AnnotationToAttributeMapperInterface
{
    private AnnotationToAttributeMapper $annotationToAttributeMapper;

    /**
     * Avoid circular reference
     */
    public function autowire(AnnotationToAttributeMapper $annotationToAttributeMapper): void
    {
        $this->annotationToAttributeMapper = $annotationToAttributeMapper;
    }

    public function isCandidate(mixed $value): bool
    {
        return $value instanceof ArrayItemNode;
    }

    /**
     * @param ArrayItemNode $arrayItemNode
     */
    public function map($arrayItemNode): Expr
    {
        $valueExpr = $this->annotationToAttributeMapper->map($arrayItemNode->value);

        if ($valueExpr === DocTagNodeState::REMOVE_ARRAY) {
            return new ArrayItem(new String_($valueExpr), null);
        }

        if ($arrayItemNode->key !== null) {
            /** @var Expr $keyExpr */
            $keyExpr = $this->annotationToAttributeMapper->map($arrayItemNode->key);
        } else {
            if ($this->hasNoParenthesesAnnotation($arrayItemNode)) {
                try {
                    RectorAssert::className(ltrim((string) $arrayItemNode->value, '@'));

                    $identifierTypeNode = new IdentifierTypeNode($arrayItemNode->value);
                    $arrayItemNode->value = new DoctrineAnnotationTagValueNode($identifierTypeNode);

                    return $this->map($arrayItemNode);
                } catch (InvalidArgumentException) {
                }
            }

            $keyExpr = null;
        }

        // @todo how to skip natural integer keys?

        return new ArrayItem($valueExpr, $keyExpr);
    }

    private function hasNoParenthesesAnnotation(ArrayItemNode $arrayItemNode): bool
    {
        if ($arrayItemNode->value instanceof StringNode) {
            return false;
        }

        if (! is_string($arrayItemNode->value)) {
            return false;
        }

        if (! str_starts_with($arrayItemNode->value, '@')) {
            return false;
        }

        return ! str_ends_with($arrayItemNode->value, ')');
    }
}
