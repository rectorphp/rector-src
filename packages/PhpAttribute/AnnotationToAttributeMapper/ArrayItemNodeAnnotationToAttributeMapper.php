<?php

declare(strict_types=1);

namespace Rector\PhpAttribute\AnnotationToAttributeMapper;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use Rector\BetterPhpDocParser\PhpDoc\ArrayItemNode;
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\PhpAttribute\AnnotationToAttributeMapper;
use Rector\PhpAttribute\Contract\AnnotationToAttributeMapperInterface;
use Rector\PhpAttribute\Enum\DocTagNodeState;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @implements AnnotationToAttributeMapperInterface<ArrayItemNode>
 */
final class ArrayItemNodeAnnotationToAttributeMapper implements AnnotationToAttributeMapperInterface
{
    private AnnotationToAttributeMapper $annotationToAttributeMapper;

    /**
     * Avoid circular reference
     */
    #[Required]
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
            $keyValue = match ($arrayItemNode->kindKeyQuoted) {
                String_::KIND_SINGLE_QUOTED => "'" . $arrayItemNode->key . "'",
                String_::KIND_DOUBLE_QUOTED => '"' . $arrayItemNode->key . '"',
                default => $arrayItemNode->key,
            };

            /** @var Expr $keyExpr */
            $keyExpr = $this->annotationToAttributeMapper->map($keyValue);
        } else {
            $keyExpr = null;

            if (is_string($arrayItemNode->value) && str_starts_with($arrayItemNode->value, '@') && ! str_ends_with(
                $arrayItemNode->value,
                ')'
            )) {
                $identifierTypeNode = new IdentifierTypeNode($arrayItemNode->value);
                $arrayItemNode->value = new DoctrineAnnotationTagValueNode($identifierTypeNode);

                return $this->map($arrayItemNode);
            }
        }

        // @todo how to skip natural integer keys?

        return new ArrayItem($valueExpr, $keyExpr);
    }
}
