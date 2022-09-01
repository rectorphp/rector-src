<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope;

use PHPStan\Analyser\MutatingScope;
use PHPStan\Analyser\ScopeContext;
use PHPStan\Analyser\ScopeFactory as PHPStanScopeFactory;

final class ScopeFactory
{
    public function __construct(
        private readonly PHPStanScopeFactory $phpStanScopeFactory
    ) {
    }

    public function createFromFile(string $filePath): MutatingScope
    {
        $scopeContext = ScopeContext::create($filePath);
        return $this->phpStanScopeFactory->create($scopeContext);
    }
}
