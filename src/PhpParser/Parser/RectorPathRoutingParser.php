<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\Parser;

use PhpParser\Node\Stmt;
use PHPStan\Parser\Parser;

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
        private readonly Parser $phpstanPathRoutingParser,
        private readonly Parser $currentPhpVersionRichParser,
    ) {
    }

    /**
     * @return Stmt[]
     */
    public function parseFile(string $file): array
    {
        // for tests, always parse nodes with directly rich parser to be aware of types
        if (defined('PHPUNIT_COMPOSER_INSTALL')) {
            return $this->currentPhpVersionRichParser->parseFile($file);
        }

        return $this->phpstanPathRoutingParser->parseFile($file);
    }

    /**
     * @return Stmt[]
     */
    public function parseString(string $sourceCode): array
    {
        return $this->phpstanPathRoutingParser->parseString($sourceCode);
    }
}
