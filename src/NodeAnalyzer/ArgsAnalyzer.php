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
<<<<<<< HEAD
        return array_any($args, fn (Arg $arg): bool => $arg->name instanceof Identifier);
=======
        return array_any($args, fn ($arg): bool => $arg->name instanceof Identifier);
>>>>>>> 424f600506 ([php] bump to PHP 8.4 syntax)
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

    /**
     * @param Arg[] $args
     */
    public function resolveFirstNamedArgPosition(array $args): ?int
    {
        $position = 0;
        foreach ($args as $arg) {
            if ($arg->name instanceof Identifier) {
                return $position;
            }

            ++$position;
        }

        return null;
    }
}
