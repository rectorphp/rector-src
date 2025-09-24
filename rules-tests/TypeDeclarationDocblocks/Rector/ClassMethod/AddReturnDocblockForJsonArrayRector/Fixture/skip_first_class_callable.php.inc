<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForJsonArrayRector\Fixture;

final class SkipFirstClassCallable
{
    public function provide(string $contents)
    {
        return json_decode(...);
    }
}

?>