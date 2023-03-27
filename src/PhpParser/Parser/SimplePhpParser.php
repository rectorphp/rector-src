<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\Parser;

use Nette\Utils\FileSystem;
use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NodeConnectingVisitor;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use Rector\Core\PhpParser\NodeTraverser\CleanVisitorNodeTraverser;

final class SimplePhpParser
{
    private readonly Parser $phpParser;

    public function __construct(private readonly CleanVisitorNodeTraverser $cleanVisitorNodeTraverser)
    {
        $parserFactory = new ParserFactory();
        $this->phpParser = $parserFactory->create(ParserFactory::PREFER_PHP7);
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

        $this->cleanVisitorNodeTraverser->addVisitor(new NodeConnectingVisitor());
        return $this->cleanVisitorNodeTraverser->traverse($stmts);
    }
}
