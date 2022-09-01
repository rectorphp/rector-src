<?php

declare(strict_types=1);

namespace Rector\Caching\FileSystem;

use PhpParser\Node\Stmt;
use PHPStan\Analyser\MutatingScope;
use PHPStan\Analyser\NodeScopeResolver;
use PHPStan\Dependency\DependencyResolver as PHPStanDependencyResolver;
use Rector\Core\Util\Reflection\PrivatesAccessor;

final class DependencyResolver
{
    public function __construct(
        private readonly NodeScopeResolver $nodeScopeResolver,
        private readonly PHPStanDependencyResolver $phpStanDependencyResolver,
        private readonly PrivatesAccessor $privatesAccessor
    ) {
    }

    /**
     * @return string[]
     */
    public function resolveDependencies(Stmt $stmt, MutatingScope $mutatingScope): array
    {
        $analysedFileAbsolutesPaths = $this->privatesAccessor->getPrivateProperty(
            $this->nodeScopeResolver,
            'analysedFiles'
        );

        $nodeDependencies = $this->phpStanDependencyResolver->resolveDependencies($stmt, $mutatingScope);
        return $nodeDependencies->getFileDependencies($mutatingScope->getFile(), $analysedFileAbsolutesPaths);
    }
}
