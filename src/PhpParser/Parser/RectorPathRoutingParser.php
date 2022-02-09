<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\Parser;

use ParseError;
use PhpParser\Node\Stmt;
use PHPStan\Parser\CachedParser;
use PHPStan\Parser\Parser;
use PHPStan\Parser\PathRoutingParser;

/**
 * Mirror to @see \PHPStan\Parser\PathRoutingParser
 *
 * @api Used in PHPStan internals for parsing nodes:
 * 1) with types for tests
 * 2) removing unsupported PHP-version code on real run
 *
 * Fixes https://github.com/rectorphp/rector/issues/6970
 */
final class RectorPathRoutingParser implements Parser
{
    public function __construct(
        private readonly PathRoutingParser $phpstanPathRoutingParser,
        private readonly CachedParser $currentPhpVersionRichParser,
    ) {
    }

    /**
     * @return Stmt[]
     */
    public function parseFile(string $file): array
    {
        try {
            // try informative parser to let PHPStan know the types
            return $this->currentPhpVersionRichParser->parseFile($file);
        } catch (ParseError) {
            // fallback to routing parser
            return $this->phpstanPathRoutingParser->parseFile($file);
        }
    }

    /**
     * @return Stmt[]
     */
    public function parseString(string $sourceCode): array
    {
        return $this->phpstanPathRoutingParser->parseString($sourceCode);
    }
}
