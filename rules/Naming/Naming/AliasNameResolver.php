<?php

declare(strict_types=1);

namespace Rector\Naming\Naming;

use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\GroupUse;

final class AliasNameResolver
{
    public function __construct(
        private readonly UseImportsResolver $useImportsResolver,
    ) {
    }

    public function resolveByName(Name $name): ?string
    {
        $uses = $this->useImportsResolver->resolveForNode($name);
        $nameString = $name->toString();

        foreach ($uses as $use) {
            $prefix = $use instanceof GroupUse
                ? $use->prefix . '\\'
                : '';

            foreach ($use->uses as $useUse) {
                if (! $useUse->alias instanceof Identifier) {
                    continue;
                }

                $name = $prefix . $useUse->name->toString();
                if ($name !== $nameString) {
                    continue;
                }

                return (string) $useUse->getAlias();
            }
        }

        return null;
    }
}
