<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\FixturePhp80;

/**
 * mixed only allowed as standalone type https://3v4l.org/ugpkM
 */
final class WithNativeReturnMixedAndOtherType
{
    public function getData()
    {
        if (rand(0, 1)) {
            return $this->get();
        }

        return $this->got();
    }

    private function get(): mixed
    {
        return null;
    }

    private function got(): int
    {
        return 1;
    }
}