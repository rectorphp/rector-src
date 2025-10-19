<?php

declare(strict_types=1);

namespace Rector\PhpParser\Node\CustomNode;

use PhpParser\Node\ContainsStmts;
use PhpParser\Node\Stmt;

/**
 * Inspired by https://github.com/phpstan/phpstan-src/commit/ed81c3ad0b9877e6122c79b4afda9d10f3994092
 */
final class FileWithoutNamespace extends Stmt implements ContainsStmts
{
    /**
     * @param Stmt[] $stmts
     */
    public function __construct(
        public array $stmts
    ) {
        parent::__construct();
    }

    public function getType(): string
    {
        return 'FileWithoutNamespace';
    }

    /**
     * @return string[]
     */
    public function getSubNodeNames(): array
    {
        return ['stmts'];
    }

    /**
     * @return Stmt[]
     */
    public function getStmts(): array
    {
        return $this->stmts;
    }
}
