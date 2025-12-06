<?php

declare(strict_types=1);

namespace Rector\PhpParser\Node;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Namespace_;

final class FileNode extends Stmt
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
        return 'CustomNode_File';
    }

    public function getSubNodeNames(): array
    {
        return ['stmts'];
    }

    public function isNamespaced(): bool
    {
        foreach ($this->stmts as $stmt) {
            if ($stmt instanceof Stmt\Namespace_) {
                return true;
            }
        }

        return false;
    }

    public function getNamespace(): ?Stmt\Namespace_
    {
        /** @var Namespace_[] $namespaces */
        $namespaces = array_filter($this->stmts, static fn (Stmt $stmt): bool => $stmt instanceof Namespace_);

        if (count($namespaces) === 1) {
            return current($namespaces);
        }

        return null;
    }
}
