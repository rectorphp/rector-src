<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class SkipVoidType
{
    public function run()
    {
        return $this->execute();
    }

    private function execute(): void
    {
    }
}
