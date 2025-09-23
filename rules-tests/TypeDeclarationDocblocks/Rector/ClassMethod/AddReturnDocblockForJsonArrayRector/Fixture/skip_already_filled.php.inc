<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForJsonArrayRector\Fixture;

final class SkipAlreadyFilled
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public function provide(string $contents): array
    {
        return json_decode($contents, true);
    }
}
