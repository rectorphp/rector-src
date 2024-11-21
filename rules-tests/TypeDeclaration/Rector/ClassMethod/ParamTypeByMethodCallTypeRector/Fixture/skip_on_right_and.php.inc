<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector\Fixture;

final class SkipOnRightAnd
{
    public string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public static function tryFromUntrusted($code): ?self
    {
        $ok = \is_string($code) && self::isCodeOk($code);
        if (! $ok) {
            return null;
        }

        return new self($code);
    }

    private static function isCodeOk(string $code): bool
    {
        return true;
    }
}