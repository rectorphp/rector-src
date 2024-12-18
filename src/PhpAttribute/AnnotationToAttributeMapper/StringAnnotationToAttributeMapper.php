<?php

declare(strict_types=1);

namespace Rector\PhpAttribute\AnnotationToAttributeMapper;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\String_;
use Rector\NodeTypeResolver\Node\AttributeKey;
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
        if (strtolower($value) === 'true') {
            return new ConstFetch(new Name('true'));
        }

        if (strtolower($value) === 'false') {
            return new ConstFetch(new Name('false'));
        }

        if (strtolower($value) === 'null') {
            return new ConstFetch(new Name('null'));
        }

        // number as string to number
        if (is_numeric($value) && strlen((string) (int) $value) === strlen($value)) {
            return Int_::fromString($value);
        }

        if (str_contains($value, "'") && ! str_contains($value, "\n")) {
            $kind = String_::KIND_DOUBLE_QUOTED;
        } else {
            $kind = String_::KIND_SINGLE_QUOTED;
        }

        if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
            $value = trim($value, '"');
        }

        return new String_($value, [
            AttributeKey::KIND => $kind,
        ]);
    }
}
