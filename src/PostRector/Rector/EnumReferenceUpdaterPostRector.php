<?php

declare(strict_types=1);

namespace Rector\PostRector\Rector;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\Renaming\Collector\RenamedEnumCaseCollector;

final class EnumReferenceUpdaterPostRector extends AbstractPostRector
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly NodeTypeResolver $nodeTypeResolver,
        private readonly RenamedEnumCaseCollector $renamedEnumCaseCollector,
    ) {
    }

    public function enterNode(Node $node): Node|null
    {
        if (! $node instanceof ClassConstFetch) {
            return null;
        }

        return $this->refactorClassConstFetch($node);
    }

    private function refactorClassConstFetch(ClassConstFetch $classConstFetch): ?Node
    {
        if (! $classConstFetch->class instanceof Name) {
            return null;
        }

        if (! $classConstFetch->name instanceof Identifier) {
            return null;
        }

        if ($this->nodeTypeResolver->getType($classConstFetch->class)->isEnum()->no()) {
            return null;
        }

        $constName = $classConstFetch->name->toString();

        // Skip "class" constant
        if ($constName === 'class') {
            return null;
        }

        $enumName = $this->nodeNameResolver->getName($classConstFetch->class);
        if (! $this->renamedEnumCaseCollector->has($enumName)) {
            return null;
        }

        $pascalCaseName = $this->convertToPascalCase($constName);
        if ($constName !== $pascalCaseName) {
            $classConstFetch->name = new Identifier($pascalCaseName);
            return $classConstFetch;
        }

        return null;
    }

    private function convertToPascalCase(string $name): string
    {
        $parts = explode('_', strtolower($name));
        return implode('', array_map(ucfirst(...), $parts));
    }
}
