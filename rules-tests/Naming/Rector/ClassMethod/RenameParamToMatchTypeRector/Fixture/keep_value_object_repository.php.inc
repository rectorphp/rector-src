<?php

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Fixture;

use Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Source\ValueObject\Repository;

final class KeepValueObjectRepository
{
    public function run(Repository $repository): void
    {
    }
}
