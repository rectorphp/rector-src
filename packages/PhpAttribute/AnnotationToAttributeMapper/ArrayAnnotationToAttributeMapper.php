<?php

declare(strict_types=1);

namespace Rector\PhpAttribute\AnnotationToAttributeMapper;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use Rector\PhpAttribute\AnnotationToAttributeMapper;
use Rector\PhpAttribute\Contract\AnnotationToAttributeMapperInterface;
use Rector\PhpAttribute\Enum\DocTagNodeState;
use Symfony\Contracts\Service\Attribute\Required;
use Webmozart\Assert\Assert;

/**
 * @implements AnnotationToAttributeMapperInterface<mixed[]>
 */
final class ArrayAnnotationToAttributeMapper implements AnnotationToAttributeMapperInterface
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
        return is_array($value);
    }

    /**
     * @param mixed[] $value
     */
    public function map($value): Expr
    {
        $arrayItems = [];

        foreach ($value as $key => $singleValue) {
            $valueExpr = $this->annotationToAttributeMapper->map($singleValue);

            // remove node
            if ($valueExpr === DocTagNodeState::REMOVE_ARRAY) {
                continue;
            }

            Assert::isInstanceOf($valueExpr, Expr::class);

            // remove value
            if ($this->isRemoveArrayPlaceholder($singleValue)) {
                continue;
            }

            $keyExpr = null;
            if (! is_int($key)) {
                $keyExpr = $this->annotationToAttributeMapper->map($key);
                Assert::isInstanceOf($keyExpr, Expr::class);
            }

            $arrayItems[] = new ArrayItem($valueExpr, $keyExpr);
        }

        return new Array_($arrayItems);
    }

    private function isRemoveArrayPlaceholder(mixed $value): bool
    {
        if (! is_array($value)) {
            return false;
        }

        return in_array(DocTagNodeState::REMOVE_ARRAY, $value, true);
    }
}
