<?php

declare(strict_types=1);

namespace Rector\PhpAttribute\AnnotationToAttributeMapper;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Rector\PhpAttribute\Contract\AnnotationToAttributeMapperInterface;
use Rector\Validation\RectorAssert;
use Webmozart\Assert\InvalidArgumentException;

/**
 * @implements AnnotationToAttributeMapperInterface<string>
 */
final class ClassConstFetchAnnotationToAttributeMapper implements AnnotationToAttributeMapperInterface
{
    public function isCandidate(mixed $value): bool
    {
        if (! is_string($value)) {
            return false;
        }

        if (! str_contains($value, '::')) {
            return false;
        }

        // is quoted? skip it
        return ! str_starts_with($value, '"');
    }

    /**
     * @param string $value
     * @return String_|ClassConstFetch
     */
    public function map($value): Node
    {
        $values = explode('::', $value);

        if (count($values) !== 2) {
            return new String_($value);
        }

        [$class, $constant] = $values;
        if ($class === '') {
            return new String_($value);
        }

        try {
            RectorAssert::className($class);
            RectorAssert::propertyName($constant);
        } catch (InvalidArgumentException) {
            return new String_($value);
        }

        return new ClassConstFetch(new Name($class), $constant);
    }
}
