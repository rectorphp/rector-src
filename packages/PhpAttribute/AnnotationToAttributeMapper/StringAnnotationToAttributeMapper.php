<?php

declare(strict_types=1);

namespace Rector\PhpAttribute\AnnotationToAttributeMapper;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Rector\PhpAttribute\Contract\AnnotationToAttributeMapperInterface;

/**
 * @implements AnnotationToAttributeMapperInterface<string>
 */
final class StringAnnotationToAttributeMapper implements AnnotationToAttributeMapperInterface
{
    public function isCandidate(mixed $value): bool
    {
        return is_string($value);
    }

    /**
     * @param string $value
     */
    public function map($value): Expr
    {
        if ($value === 'true') {
            return new ConstFetch(new Name('true'));
        }

        if ($value === 'false') {
            return new ConstFetch(new Name('false'));
        }

        if ($value === 'null') {
            return new ConstFetch(new Name('null'));
        }

        return new String_($value);
    }
}
