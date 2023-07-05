<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PHPStan\Analyser\MutatingScope;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\ScopeFactory;

final class ScopeAnalyzer
{
    /**
     * @var array<class-string<Node>>
     */
    private const NO_SCOPE_NODES = [Name::class, Identifier::class, Param::class, Arg::class];

    public function __construct(
        private readonly ScopeFactory $scopeFactory
    ) {
    }

    public function hasScope(Node $node): bool
    {
        foreach (self::NO_SCOPE_NODES as $noScopeNode) {
            if ($node instanceof $noScopeNode) {
                return false;
            }
        }

        return true;
    }

    public function resolveScope(
        Node $node,
        string $filePath,
        ?MutatingScope $mutatingScope = null
    ): ?MutatingScope {
        if ($mutatingScope instanceof MutatingScope) {
            return $mutatingScope;
        }

        // on File level
        if ($node instanceof Stmt && $node->getAttribute(AttributeKey::STATEMENT_DEPTH) === 0) {
            return $this->scopeFactory->createFromFile($filePath);
        }

        // too deep Expr, eg: $$param = $$bar = self::decodeValue($result->getItem()->getTextContent());
        if ($node instanceof Expr && $node->getAttribute(AttributeKey::EXPRESSION_DEPTH) >= 2) {
            return $this->scopeFactory->createFromFile($filePath);
        }

        /**
         * Node and parent Node doesn't has Scope, and Node Start token pos is < 0,
         * it means the node and parent node just re-printed, the Scope need to be resolved from file
         */
        if ($node->getStartTokenPos() < 0) {
            return $this->scopeFactory->createFromFile($filePath);
        }

        return null;
    }
}
