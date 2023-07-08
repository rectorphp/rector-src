<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\Parser;

use Nette\Utils\FileSystem;
use PhpParser\Node\Stmt;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use Rector\Core\PhpParser\NodeTraverser\NodeConnectingTraverser;
use Rector\NodeTypeResolver\PHPStan\Scope\NodeVisitor\AssignedToNodeVisitor;

final class SimplePhpParser
{
    private readonly Parser $phpParser;

    public function __construct(
        private readonly NodeConnectingTraverser $nodeConnectingTraverser
    ) {
        $parserFactory = new ParserFactory();
        $this->phpParser = $parserFactory->create(ParserFactory::PREFER_PHP7);

        $this->nodeConnectingTraverser->addVisitor(new AssignedToNodeVisitor());
    }

    /**
     * @api tests
     * @return Stmt[]
     */
    public function parseFile(string $filePath): array
    {
        $fileContent = FileSystem::read($filePath);
        return $this->parseString($fileContent);
    }

    /**
     * @return Stmt[]
     */
    public function parseString(string $fileContent): array
    {
        $stmts = $this->phpParser->parse($fileContent);
        if ($stmts === null) {
            return [];
        }

        return $this->nodeConnectingTraverser->traverse($stmts);
    }
}
