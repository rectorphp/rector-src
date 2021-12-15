<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\Parser;

use PhpParser\Lexer;
use PhpParser\Lexer\Emulative;
use PhpParser\Node\Stmt;
use PHPStan\Parser\Parser;
use PHPStan\Parser\ParserErrorsException;
use Rector\Core\PhpParser\ValueObject\StmtsAndTokens;
use Rector\Core\Util\StringUtils;
use Symplify\PackageBuilder\Reflection\PrivatesAccessor;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RectorParser
{
    /**
     * @var string
     * @see https://regex101.com/r/txAwRA/1
     */
    private const FN_ERROR_REGEX = '#^Syntax error, unexpected T_FN, expecting T_STRING or \'\(\' on line#';

    public function __construct(
        private readonly Lexer $lexer,
        private Parser $parser,
        private readonly PrivatesAccessor $privatesAccessor
    ) {
    }

    /**
     * @return Stmt[]
     */
    public function parseFile(SmartFileInfo $smartFileInfo): array
    {
        try {
            return $this->parser->parseFile($smartFileInfo->getRealPath());
        } catch (ParserErrorsException $parserErrorsException) {
            $message = $parserErrorsException->getMessage();

            if (! StringUtils::isMatch($message, self::FN_ERROR_REGEX)) {
                throw $parserErrorsException;
            }

            $parser = $this->parser;

            $lexer = &\Closure::bind(static fn &($parser) => $parser->lexer, null, $parser)($parser);
            $lexer = new Emulative([
                'phpVersion' => Emulative::PHP_7_3,
            ]);

            return $this->parser->parseFile($smartFileInfo->getRealPath());
        }
    }

    public function parseFileToStmtsAndTokens(SmartFileInfo $smartFileInfo): StmtsAndTokens
    {
        $stmts = $this->parseFile($smartFileInfo);
        $tokens = $this->lexer->getTokens();

        return new StmtsAndTokens($stmts, $tokens);
    }
}
