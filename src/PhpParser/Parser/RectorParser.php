<?php

declare(strict_types=1);

namespace Rector\PhpParser\Parser;

use PhpParser\Lexer;
use PhpParser\Node\Stmt;
use PHPStan\Parser\Parser;
use Rector\PhpParser\ValueObject\StmtsAndTokens;

final readonly class RectorParser
{
    public function __construct(
        private Lexer $lexer,
        private Parser $parser,
    ) {
    }

    /**
     * @api used by rector-symfony
     *
     * @return Stmt[]
     */
    public function parseFile(string $filePath): array
    {
        return $this->parser->parseFile($filePath);
    }

    /**
     * @return Stmt[]
     */
    public function parseString(string $filePath): array
    {
        return $this->parser->parseString($filePath);
    }

    public function parseFileContentToStmtsAndTokens(string $fileContent): StmtsAndTokens
    {
        $stmts = $this->parser->parseString($fileContent);
        $tokens = $this->lexer->getTokens();

        return new StmtsAndTokens($stmts, $tokens);
    }
}
