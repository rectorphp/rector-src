<?php

declare(strict_types=1);

namespace Rector\PhpParser\Parser;

use PhpParser\Lexer;
use PhpParser\Node\Stmt;
use PHPStan\Parser\Parser;
use Rector\PhpParser\ValueObject\StmtsAndTokens;
use Rector\Util\Reflection\PrivatesAccessor;

final readonly class RectorParser
{
    public function __construct(
        private Parser $parser,
        private PrivatesAccessor $privatesAccessor
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
    public function parseString(string $fileContent): array
    {
        return $this->parser->parseString($fileContent);
    }

    public function parseFileContentToStmtsAndTokens(string $fileContent): StmtsAndTokens
    {
        $stmts = $this->parser->parseString($fileContent);

        $innerParser = $this->privatesAccessor->getPrivateProperty($this->parser, 'parser');
        $tokens = $innerParser->getTokens();

        return new StmtsAndTokens($stmts, $tokens);
    }
}
