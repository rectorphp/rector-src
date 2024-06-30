<?php

declare(strict_types=1);

namespace Rector\UseImports\ValueObject;

use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use Webmozart\Assert\Assert;

final readonly class UseImportsScope
{
    /**
     * @param array<Use_> $uses
     */
    public function __construct(
        private ?Namespace_ $namespace,
        private int $namespaceCount,
        private array $uses
    ) {
        Assert::allIsInstanceOfAny($uses, [Use_::class]);
    }

    /**
     * @api will be used
     */
    public function getNamespace(): ?Namespace_
    {
        return $this->namespace;
    }

    /**
     * @api will be used
     */
    public function getNamespaceCount(): int
    {
        return $this->namespaceCount;
    }

    /**
     * @return array<Use_>
     */
    public function getUses(): array
    {
        return $this->uses;
    }
}
