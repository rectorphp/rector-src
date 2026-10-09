<?php

declare(strict_types=1);

namespace Rector\PhpParser\Parser;

use PhpParser\Node\Stmt;
use PhpParser\ParserFactory;
use PhpParser\PhpVersion;
use PHPStan\Parser\Parser;
use PHPStan\Parser\RichParser;
use Rector\Php\PhpVersionProvider;
use Rector\PhpParser\ValueObject\StmtsAndTokens;
use Rector\Util\Reflection\PrivatesAccessor;
use Rector\ValueObject\PhpVersion as CorePhpVersion;

final readonly class RectorParser
{
    /**
     * @param RichParser $parser
     */
    public function __construct(
        private Parser $parser,
        private PrivatesAccessor $privatesAccessor,
        private PhpVersionProvider $phpVersionProvider
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
        $parser = $this->resolveConfiguredVersionParser();

        $stmts = $parser->parseString($fileContent);

        $innerParser = $this->privatesAccessor->getPrivateProperty($parser, 'parser');
        $tokens = $innerParser->getTokens();

        return new StmtsAndTokens($stmts, $tokens);
    }

    private function resolveConfiguredVersionParser(): Parser
    {
        $phpVersion = $this->phpVersionProvider->provide();

        // on 8.0+ the default newest parser is correct;
        // only pre-8.0 syntax (e.g. curly offset access) needs an older parser
        if ($phpVersion >= CorePhpVersion::PHP_80) {
            return $this->parser;
        }

        // don't mutate the shared PHPStan parser service, clone to avoid reuse on the next file
        $phpstanParser = clone $this->parser;

        $parser = new ParserFactory()->createForVersion(
            PhpVersion::fromComponents(intdiv($phpVersion, 10000), intdiv($phpVersion % 10000, 100))
        );
        $this->privatesAccessor->setPrivateProperty($phpstanParser, 'parser', $parser);

        return $phpstanParser;
    }
}
