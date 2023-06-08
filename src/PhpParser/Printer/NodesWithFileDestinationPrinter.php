<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\Printer;

use Rector\FileSystemRector\ValueObject\AddedFileWithNodes;
use Rector\PostRector\Application\PostFileProcessor;

final class NodesWithFileDestinationPrinter
{
    public function __construct(
        private readonly BetterStandardPrinter $nodePrinter,
        private readonly PostFileProcessor $postFileProcessor
    ) {
    }

    public function printNodesWithFileDestination(AddedFileWithNodes $addedFileWithNodes): string
    {
        $nodes = $this->postFileProcessor->traverse($addedFileWithNodes->getNodes());
        return $this->nodePrinter->prettyPrintFile($nodes);
    }
}
