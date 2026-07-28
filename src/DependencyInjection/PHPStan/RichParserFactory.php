<?php

declare(strict_types=1);

namespace Rector\DependencyInjection\PHPStan;

use PhpParser\NodeVisitor;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use PHPStan\Analyser\Ignore\IgnoreLexer;
use PHPStan\DependencyInjection\DirectExtensionsCollection;
use PHPStan\Parser\RichParser;

/**
 * Creates PHPStan RichParser with only the node visitors Rector needs,
 * to avoid issues caused by node replacement, like @see https://github.com/rectorphp/rector/issues/9492
 *
 * The visitors have to be passed here, as PHPStan autowires them into every RichParser
 * service definition, see PHPStan\DependencyInjection\AutowiredExtensionsExtension
 */
final readonly class RichParserFactory
{
    /**
     * @param NodeVisitor[] $nodeVisitors
     */
    public function __construct(
        private Parser $parser,
        private NameResolver $nameResolver,
        private IgnoreLexer $ignoreLexer,
        private array $nodeVisitors
    ) {
    }

    /**
     * @api used by config/phpstan/parser.neon
     */
    public function create(): RichParser
    {
        return new RichParser(
            $this->parser,
            $this->nameResolver,
            new DirectExtensionsCollection($this->nodeVisitors),
            $this->ignoreLexer
        );
    }
}
