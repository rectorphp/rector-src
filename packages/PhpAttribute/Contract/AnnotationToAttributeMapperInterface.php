<?php

declare(strict_types=1);

namespace Rector\PhpAttribute\Contract;

use PhpParser\Node\Expr;

/**
 * @template T as mixed
 */
interface AnnotationToAttributeMapperInterface
{
    public function isCandidate(mixed $value): bool;

    /**
     * @param T $value
     * @return Expr|Expr[]
     */
    public function map($value): array|Expr;
}
