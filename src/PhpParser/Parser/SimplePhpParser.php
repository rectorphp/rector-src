<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\Parser;

use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NodeConnectingVisitor;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use Symplify\SmartFileSystem\SmartFileSystem;

final class SimplePhpParser
{
    private Parser $phpParser;

    public function __construct(
<<<<<<< HEAD
        private SmartFileSystem $smartFileSystem,
=======
<<<<<<< HEAD
        private SmartFileSystem $smartFileSystem
=======
        private SmartFileSystem $smartFileSystem,
>>>>>>> d3887c673... warn about self/static/parent object reference
>>>>>>> warn about self/static/parent object reference
    ) {
        $parserFactory = new ParserFactory();
        $this->phpParser = $parserFactory->create(ParserFactory::PREFER_PHP7);
    }

    /**
     * @return Stmt[]
     */
    public function parseFile(string $filePath): array
    {
        $fileContent = $this->smartFileSystem->readFile($filePath);
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

        $nodeTraverser = new NodeTraverser();
        $nodeTraverser->addVisitor(new NodeConnectingVisitor());

        return $nodeTraverser->traverse($stmts);
    }
}
