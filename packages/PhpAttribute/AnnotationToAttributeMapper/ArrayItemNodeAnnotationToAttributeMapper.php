<?php

declare(strict_types=1);

namespace Rector\PhpAttribute\AnnotationToAttributeMapper;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use Rector\BetterPhpDocParser\PhpDoc\ArrayItemNode;
use Rector\PhpAttribute\AnnotationToAttributeMapper;
use Rector\PhpAttribute\Contract\AnnotationToAttributeMapperInterface;
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
        }

        // @todo how to skip natural integer keys?

        return new ArrayItem($valueExpr, $keyExpr);
    }
}
