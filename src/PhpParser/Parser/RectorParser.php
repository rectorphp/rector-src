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
