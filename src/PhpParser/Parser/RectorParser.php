<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\Parser;

use PhpParser\Lexer;
use PhpParser\Node\Stmt;
use PHPStan\Parser\Parser;
use Rector\Core\PhpParser\ValueObject\StmtsAndTokens;

final class RectorParser
{
    public function __construct(
        private readonly Lexer $lexer,
        private readonly Parser $parser,
    ) {
    }

    /**
     * @return Stmt[]
     */
    public function parseFile(string $filePath): array
    {
        return $this->parser->parseFile($filePath);
    }

    public function parseFileToStmtsAndTokens(string $filePath): StmtsAndTokens
    {
        $stmts = $this->parseFile($filePath);
        $tokens = $this->lexer->getTokens();

        return new StmtsAndTokens($stmts, $tokens);
    }
}
