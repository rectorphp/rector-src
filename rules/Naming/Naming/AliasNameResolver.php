<?php

declare(strict_types=1);

namespace Rector\Naming\Naming;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;

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
            $prefix = $this->useImportsResolver->resolvePrefix($use);

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

    public function resolveAliasOriginalNameFromBareUse(Node $node, string $nameString): ?string
    {
        $nameString = str_contains($nameString, '\\')
            ? Strings::before($nameString, '\\', -1)
            : $nameString;
        $uses = $this->useImportsResolver->resolveBareUsesForNode($node);

        foreach ($uses as $use) {
            $prefix = $this->useImportsResolver->resolvePrefix($use);

            foreach ($use->uses as $useUse) {
                if (! $useUse->alias instanceof Identifier) {
                    continue;
                }

                if ($useUse->alias->toString() !== $nameString) {
                    continue;
                }

                return $prefix . $useUse->name->toString();
            }
        }

        return null;
    }
}
