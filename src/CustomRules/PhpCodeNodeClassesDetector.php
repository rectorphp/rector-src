<?php

declare(strict_types=1);

namespace Rector\CustomRules;

use PhpParser\NodeTraverser;
use Rector\CustomRules\NodeVisitor\NodeClassNodeVisitor;
use Rector\PhpParser\Parser\SimplePhpParser;

/**
 * @see \Rector\Tests\CustomRules\PhpCodeNodeClassesDetectorTest
 */
final class PhpCodeNodeClassesDetector
{
    public function __construct(
        private readonly SimplePhpParser $simplePhpParser,
        private readonly NodeClassNodeVisitor $nodeClassNodeVisitor,
    ) {
    }

    public function detect(string $phpContents): array
    {
        if (! str_starts_with($phpContents, '<?php')) {
            // prepend with PHP opening tag to make parse PHP code
            $phpContents = '<?php ' . $phpContents;
        }

        try {
            $nodes = $this->simplePhpParser->parseString($phpContents);
        } catch (\Throwable) {
            // @todo try recover here
            return [];
        }

        return $this->traverseNodesAndFindNodeClasses($nodes);
    }

    /**
     * @param \PhpParser\Node[] $nodes
     * @return string[]
     */
    private function traverseNodesAndFindNodeClasses(array $nodes): array
    {
        $nodeTraverser = new NodeTraverser();
        $nodeTraverser->addVisitor($this->nodeClassNodeVisitor);

        $nodeTraverser->traverse($nodes);

        return $this->nodeClassNodeVisitor->getFoundNodeClasses();
    }
}
