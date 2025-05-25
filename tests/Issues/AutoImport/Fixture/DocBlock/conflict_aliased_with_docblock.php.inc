<?php

namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;

use InvalidArgumentException as IAE;

class ConflictAliasedWithDocblock
{
    /**
     * @throws \InvalidArgumentException
     */
    public function test(): void
    {
    }
}

?>