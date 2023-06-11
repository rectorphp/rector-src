<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\NodeTraverser;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use Rector\Core\Contract\Rector\PhpRectorInterface;
use Rector\VersionBonding\PhpVersionedFilter;

final class RectorNodeTraverser extends NodeTraverser
{
    private bool $areNodeVisitorsPrepared = false;

<<<<<<< HEAD
=======
    /**
     * @var PhpRectorInterface[]|NodeVisitor[]
     */
    private array $activePhpRectors = [];

>>>>>>> deb36aa9b4 (make use of tags in DI)
    /**
     * @param PhpRectorInterface[] $phpRectors
     */
    public function __construct(
        private readonly iterable $phpRectors,
        private readonly PhpVersionedFilter $phpVersionedFilter
    ) {
        parent::__construct();
    }

    /**
     * @template TNode as Node
     * @param TNode[] $nodes
     * @return TNode[]
     */
    public function traverse(array $nodes): array
    {
        $this->prepareNodeVisitors();
        return parent::traverse($nodes);
    }

    /**
     * This must happen after $this->configuration is set after ProcessCommand::execute() is run,
     * otherwise we get default false positives.
     *
     * This hack should be removed after https://github.com/rectorphp/rector/issues/5584 is resolved
     */
    private function prepareNodeVisitors(): void
    {
        if ($this->areNodeVisitorsPrepared) {
            return;
        }

        // filer out by version
        $activePhpRectors = $this->phpVersionedFilter->filter($this->phpRectors);
<<<<<<< HEAD
        $this->visitors = $this->visitors === []
            ? $activePhpRectors
            : array_merge($this->visitors, $activePhpRectors);
=======

        $this->activePhpRectors = array_merge($this->visitors, $activePhpRectors);
>>>>>>> c2bfa653c4 (pass rector instances directly)

        $this->areNodeVisitorsPrepared = true;
    }
}
