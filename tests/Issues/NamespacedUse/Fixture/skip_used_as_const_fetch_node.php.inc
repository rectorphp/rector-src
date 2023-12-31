<?php

namespace Rector\Tests\Issues\NamespacedUse\Fixture;

use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;

final class SkipUsedAsConstFetchNode
{
    /**
     * @param TypeKind::* $typeKind
     */
    public function run(string $typeKind)
    {
    }
}
