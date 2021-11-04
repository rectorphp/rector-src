<?php

declare(strict_types=1);

namespace Rector\NameImporting\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\UseUse;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\ValueObject\Application\File;
use Rector\NameImporting\ValueObject\NameAndParent;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * @see \Rector\Tests\NameImporting\NodeAnalyzer\UseAnalyzer\UseAnalyzerTest
 */
final class UseAnalyzer
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    /**
     * @return array<string, NameAndParent[]>
     */
    public function resolveUsedNameNodes(File $file): array
    {
        $usedNamesByShortName = $this->resolveUsedNames($file);
        $usedClassNamesByShortName = $this->resolveUsedClassNames($file);
        $usedTraitNamesByShortName = $this->resolveTraitUseNames($file);

        return array_merge($usedNamesByShortName, $usedClassNamesByShortName, $usedTraitNamesByShortName);
    }

    /**
     * @return array<string, NameAndParent[]>
     */
    private function resolveUsedNames(File $file): array
    {
        $namesAndParentsByShortName = [];

        /** @var Name[] $names */
        $names = $this->betterNodeFinder->findInstanceOf($file->getOldStmts(), Name::class);

        foreach ($names as $name) {
            /** node name before becoming FQN - attribute from @see NameResolver */
            $originalName = $name->getAttribute(AttributeKey::ORIGINAL_NAME);
            if (! $originalName instanceof Name) {
                continue;
            }

            $parentNode = $name->getAttribute(AttributeKey::PARENT_NODE);
            if (! $parentNode instanceof Node) {
                throw new ShouldNotHappenException();
            }

            $shortName = $originalName->toString();
            $namesAndParentsByShortName[$shortName][] = new NameAndParent($name, $parentNode);
        }

        return $namesAndParentsByShortName;
    }

    /**
     * @return array<string, NameAndParent[]>
     */
    private function resolveUsedClassNames(File $file): array
    {
        $namesAndParentsByShortName = [];

        /** @var ClassLike[] $classLikes */
        $classLikes = $this->betterNodeFinder->findClassLikes($file->getOldStmts());

        foreach ($classLikes as $classLike) {
            $classLikeName = $classLike->name;
            if (! $classLikeName instanceof Identifier) {
                continue;
            }

            $name = $this->nodeNameResolver->getName($classLikeName);
            if ($name === null) {
                continue;
            }

            $namesAndParentsByShortName[$name][] = new NameAndParent($classLikeName, $classLike);
        }

        return $namesAndParentsByShortName;
    }

    /**
     * @return array<string, NameAndParent[]>
     */
    private function resolveTraitUseNames(File $file): array
    {
        $namesAndParentsByShortName = [];

        /** @var Identifier[] $identifiers */
        $identifiers = $this->betterNodeFinder->findInstanceOf($file->getOldStmts(), Identifier::class);

        foreach ($identifiers as $identifier) {
            $parentNode = $identifier->getAttribute(AttributeKey::PARENT_NODE);
            if (! $parentNode instanceof UseUse) {
                continue;
            }

            $shortName = $identifier->name;
            $namesAndParentsByShortName[$shortName][] = new NameAndParent($identifier, $parentNode);
        }

        return $namesAndParentsByShortName;
    }
}
