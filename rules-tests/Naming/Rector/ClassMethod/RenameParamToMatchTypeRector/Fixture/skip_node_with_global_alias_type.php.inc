<?php

declare(strict_types=1);

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Fixture;

final class SkipNodeWithGlobalAliasType
{
    /**
     * @param StmtsAware $stmtsAware
     */
    public function run(\PhpParser\Node $stmtsAware)
    {
    }
}
