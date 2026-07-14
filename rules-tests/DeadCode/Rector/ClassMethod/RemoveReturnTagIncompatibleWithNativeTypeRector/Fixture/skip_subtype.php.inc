<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

final class SkipSubtype
{
    /**
     * @return callable-string
     */
    public function getCallableString(): string
    {
        return 'trim';
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function getNonEmptyLowercaseString(): string
    {
        return 'abc';
    }

    /**
     * @return non-empty-uppercase-string
     */
    public function getNonEmptyUppercaseString(): string
    {
        return 'ABC';
    }

    /**
     * @return non-empty-literal-string
     */
    public function getNonEmptyLiteralString(): string
    {
        return '3v4l';
    }

    /**
     * @return decimal-int-string
     */
    public function getDecimalIntString(): string
    {
        return '123';
    }

    /**
     * @return non-decimal-int-string
     */
    public function getNonDecimalIntString(): string
    {
        return '+1';
    }
}
