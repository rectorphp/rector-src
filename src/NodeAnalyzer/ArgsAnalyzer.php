<?php

declare(strict_types=1);

namespace Rector\NodeAnalyzer;

use PhpParser\Node\Arg;
use PhpParser\Node\Identifier;
use Rector\NodeNameResolver\NodeNameResolver;

final readonly class ArgsAnalyzer
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    /**
     * @param Arg[] $args
     */
    public function hasNamedArg(array $args): bool
    {
        foreach ($args as $arg) {
            if ($arg->name instanceof Identifier) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Arg[] $args
     */
    public function resolveArgPosition(array $args, string $name, int $defaultPosition): int
    {
        foreach ($args as $position => $arg) {
            if (! $arg->name instanceof Identifier) {
                continue;
            }

            if (! $this->nodeNameResolver->isName($arg->name, $name)) {
                continue;
            }

            return $position;
        }

        return $defaultPosition;
    }
}
