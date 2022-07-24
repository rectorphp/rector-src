<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector\Source;

final class ExternalList
{
    public const FIRST = 'first';

    public const SECOND = 'second';

    public const VALUES = [self::FIRST, self::SECOND];

    /**
     * @var non-empty-string[]
     */
    static public function getArrayOfNonEmptyStrings() {
        $a = [];
        $a[] = self::FIRST;
        $a[] = self::SECOND;
        return $a;
    }

    /**
     * @var numeric-string[]
     */
    static public function getArrayOfNumericStrings() {
        $a = [];
        $a[] = 1;
        $a[] = 2;
        return $a;
    }
}
