<?php

declare(strict_types=1);

namespace Rector\PhpAttribute\AnnotationToAttributeMapper;

use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\LNumber;
use Rector\BetterPhpDocParser\ValueObject\PhpDoc\DoctrineAnnotation\CurlyListNode;
use Rector\PhpAttribute\AnnotationToAttributeMapper;
use Rector\PhpAttribute\Contract\AnnotationToAttributeMapperInterface;
use Rector\PhpAttribute\Enum\DocTagNodeState;
use Symfony\Contracts\Service\Attribute\Required;
use Webmozart\Assert\Assert;

/**
 * @implements AnnotationToAttributeMapperInterface<CurlyListNode>
 */
final class CurlyListNodeAnnotationToAttributeMapper implements AnnotationToAttributeMapperInterface
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
        return $value instanceof CurlyListNode;
    }

    /**
     * @param CurlyListNode $value
     */
    public function map($value): Array_
    {
        $arrayItems = [];
        $arrayItemNodes = $value->getValues();
        $loop = -1;

        foreach ($arrayItemNodes as $arrayItemNode) {
            $valueExpr = $this->annotationToAttributeMapper->map($arrayItemNode);

            // remove node
            if ($valueExpr === DocTagNodeState::REMOVE_ARRAY) {
                continue;
            }

            Assert::isInstanceOf($valueExpr, ArrayItem::class);

            if (! is_numeric($arrayItemNode->key)) {
                $arrayItems[] = $valueExpr;
                continue;
            }

            ++$loop;

            if ($loop === (int) $arrayItemNode->key) {
                $arrayItems[] = $valueExpr;
                continue;
            }

            $valueExpr->key = new LNumber((int) $arrayItemNode->key);
            $arrayItems[] = $valueExpr;
        }

        return new Array_($arrayItems);
    }
}
