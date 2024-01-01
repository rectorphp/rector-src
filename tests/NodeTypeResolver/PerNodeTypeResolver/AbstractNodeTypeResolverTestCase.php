<?php

declare(strict_types=1);

namespace Rector\Tests\NodeTypeResolver\PerNodeTypeResolver;

use PhpParser\Node;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Testing\TestingParser\TestingParser;

abstract class AbstractNodeTypeResolverTestCase extends AbstractLazyTestCase
{
    protected NodeTypeResolver $nodeTypeResolver;

    private BetterNodeFinder $betterNodeFinder;

    private TestingParser $testingParser;

    protected function setUp(): void
    {
        $this->betterNodeFinder = $this->make(BetterNodeFinder::class);
        $this->testingParser = $this->make(TestingParser::class);
        $this->nodeTypeResolver = $this->make(NodeTypeResolver::class);
    }

    /**
     * @template T as Node
     * @param class-string<T> $type
     * @return T[]
     */
    protected function getNodesForFileOfType(string $filePath, string $type): array
    {
        $nodes = $this->testingParser->parseFileToDecoratedNodes($filePath);
        return $this->betterNodeFinder->findInstanceOf($nodes, $type);
    }
}
