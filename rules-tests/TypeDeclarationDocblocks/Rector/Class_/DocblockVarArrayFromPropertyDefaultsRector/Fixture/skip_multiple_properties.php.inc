<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\DocblockVarArrayFromPropertyDefaultsRector\Fixture;

final class SkipMultipleProperties
{
    private array $names, $surnames = ['Jim', 'Rohn'];
}
