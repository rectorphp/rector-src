<?php

declare(strict_types=1);

namespace Rector\PostRector\Rector;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Use_;
use Rector\CodingStyle\ClassNameImport\ClassNameImportSkipper;
use Rector\CodingStyle\Node\NameImporter;
use Rector\Naming\Naming\AliasNameResolver;
use Rector\Naming\Naming\UseImportsResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PostRector\Guard\AddUseStatementGuard;

final class NameImportingPostRector extends AbstractPostRector
{
    /**
     * @var array<Use_|GroupUse>
     */
    private array $currentUses = [];

    public function __construct(
        private readonly NameImporter $nameImporter,
        private readonly ClassNameImportSkipper $classNameImportSkipper,
        private readonly UseImportsResolver $useImportsResolver,
        private readonly AliasNameResolver $aliasNameResolver,
        private readonly AddUseStatementGuard $addUseStatementGuard
    ) {
    }

    public function beforeTraverse(array $nodes)
    {
        $this->currentUses = $this->useImportsResolver->resolve();
        return $nodes;
    }

    public function enterNode(Node $node): Node|int|null
    {
        if (! $node instanceof FullyQualified) {
            return null;
        }

        if ($node->isSpecialClassName()) {
            return null;
        }

        if ($this->classNameImportSkipper->shouldSkipName($node, $this->currentUses)) {
            return null;
        }

        // make use of existing use import
        $nameInUse = $this->resolveNameInUse($node);
        if ($nameInUse instanceof Name) {
            $nameInUse->setAttribute(AttributeKey::NAMESPACED_NAME, $node->toString());
            return $nameInUse;
        }

        return $this->nameImporter->importName($node, $this->getFile());
    }

    /**
     * @param Stmt[] $stmts
     */
    public function shouldTraverse(array $stmts): bool
    {
        return $this->addUseStatementGuard->shouldTraverse($stmts, $this->getFile()->getFilePath());
    }

    private function resolveNameInUse(FullyQualified $fullyQualified): null|Name
    {
        $aliasName = $this->aliasNameResolver->resolveByName($fullyQualified, $this->currentUses);
        if (is_string($aliasName)) {
            return new Name($aliasName);
        }

        if (substr_count($fullyQualified->toCodeString(), '\\') === 1) {
            return null;
        }

        $lastName = $fullyQualified->getLast();
        foreach ($this->currentUses as $currentUse) {
            foreach ($currentUse->uses as $useUse) {
                if ($useUse->name->getLast() !== $lastName) {
                    continue;
                }

                if ($useUse->alias instanceof Identifier && $useUse->alias->toString() !== $lastName) {
                    return new Name($lastName);
                }
            }
        }

        return null;
    }
}
