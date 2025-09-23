<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForJsonArrayRector\Fixture;

final class SkipMissingArg
{
    public function provide(string $contents): array
    {
        return json_decode($contents, false);
    }
}
