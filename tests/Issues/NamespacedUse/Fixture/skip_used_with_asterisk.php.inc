<?php

namespace Rector\Core\Tests\Issues\NamespacedUse\Fixture;

use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;

final class SkipUsedWithAsterisk
{
    /**
     * @param TypeKind::* $typeKind
     */
    public function run(string $typeKind)
    {
    }
}
