<?php

declare(strict_types=1);

namespace Rector\PhpDocParser\ValueObject\NodeBuilder;

use PhpParser\Builder\Use_;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Use_ as UseStmt;

/**
 * @api
 * Fixed duplicated naming in php-parser and prevents confusion
 */
final class UseBuilder extends Use_
{
    public function __construct(Name|string $name, int $type = UseStmt::TYPE_NORMAL)
    {
        parent::__construct($name, $type);
    }
}
