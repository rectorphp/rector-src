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
    public function map($value): array|Expr
    {
        $arrayItems = [];

        foreach ($value as $key => $singleValue) {
            $valueExpr = $this->annotationToAttributeMapper->map($singleValue);

            // remove value
            if ($this->isRemoveArrayPlaceholder($singleValue)) {
                continue;
            }

            $keyExpr = null;
            if (! is_int($key)) {
                $keyExpr = $this->annotationToAttributeMapper->map($key);
            }

            if (! $valueExpr instanceof Expr) {
                dump($valueExpr);
                die;
            }

            $arrayItems[] = new ArrayItem($valueExpr, $keyExpr);
        }

        return new Array_($arrayItems);
//
//
//        foreach ($values as $key => $value) {
//            // remove the key and value? useful in case of unwrapping nested attributes
//            if (! $this->isRemoveArrayPlaceholder($value)) {
//                continue;
//            }
//
//            unset($values[$key]);
//        }
    }

    private function isRemoveArrayPlaceholder($value): bool
    {
        if (! is_array($value)) {
            return false;
        }

        return in_array(DocTagNodeState::REMOVE_ARRAY, $value, true);
    }
}
