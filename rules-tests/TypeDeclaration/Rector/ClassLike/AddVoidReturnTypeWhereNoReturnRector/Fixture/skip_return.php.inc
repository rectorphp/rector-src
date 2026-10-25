<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

final class SkipReturnAfterClosureReturn
{
    private function run($someObject)
    {
        $someData = $someObject->run('foo', function (): array {
            return [];
        }, strtotime('+6 hours'));

        return $someData[$this->get()];
    }
}
