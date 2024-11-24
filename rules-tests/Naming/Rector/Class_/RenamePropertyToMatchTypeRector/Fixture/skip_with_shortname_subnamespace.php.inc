<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

use PhpParser\Node;

final class SkipWithShortnameSubnamespace
{
    /**
     * @var Node\Stmt
     */
    private $someStmt;

    public function __construct(Node\Stmt $stmt)
    {
        $this->someStmt = $stmt;
    }
}
