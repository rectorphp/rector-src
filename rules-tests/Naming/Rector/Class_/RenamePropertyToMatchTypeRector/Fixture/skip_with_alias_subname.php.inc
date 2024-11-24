<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

use PhpParser\Node as PhpParserNode;

final class SkipWithAliasSubname
{
    /**
     * @var PhpParserNode\Stmt
     */
    private $someStmt;

    public function __construct(PhpParserNode\Stmt $stmt)
    {
        $this->someStmt = $stmt;
    }
}
