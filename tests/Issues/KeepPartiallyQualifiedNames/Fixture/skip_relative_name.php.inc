<?php

namespace Rector\Tests\Issues\KeepPartiallyQualifiedNames\Fixture;

final class SkipRelativeName
{
    public function run()
    {
        return new namespace\Sub\SomeClass();
    }
}
