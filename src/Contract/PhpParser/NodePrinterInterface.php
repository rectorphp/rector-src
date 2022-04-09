<?php

declare(strict_types=1);

namespace Rector\Core\Contract\PhpParser;

use PhpParser\Node;
use PhpParser\Node\Stmt;

/**
 * This contract allows to autowire custom printer implementation
 */
interface NodePrinterInterface
{
    /**
     * @param Node|Node[]|null $node
     */
    public function print(Node | array | null $node): string;

    /**
     * @param Stmt[] $stmts
     */
    public function prettyPrint(array $stmts): string;

    /**
     * @param Stmt[] $stmts
     */
    public function prettyPrintFile(array $stmts): string;
}
