<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node\Name\FullyQualified;
use Rector\NodeCollector\NodeCollector\NodeRepository;
use Rector\NodeNameResolver\NodeNameResolver;

final class ExternalFullyQualifiedAnalyzer
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private NodeRepository $nodeRepository
    )
    {
    }

    /**
     * @param FullyQualified[]|mixed[] $traits
     */
    public function hasExternalClassOrInterface(array $fullyQualifiedClassLikes): bool
    {
        if ($fullyQualifiedClassLikes === []) {
            return false;
        }

        foreach ($fullyQualifiedClassLikes as $classLike) {
            if (! $classLike instanceof FullyQualified) {
                return false;
            }

            /** @var string $traitName */
            $className = $this->nodeNameResolver->getName($classLike);
            $isClassFound     = (bool) $this->nodeRepository->findClass($className);
            $isInterfaceFound = (bool) $this->nodeRepository->findInterface($className);

            if ($isClassFound || $isInterfaceFound) {
                continue;
            }

            return true;
        }

        return false;
    }

    /**
     * @param FullyQualified[]|mixed[] $fullyQualifiedTraits
     */
    public function hasExternalTrait(array $fullyQualifiedTraits): bool
    {
        if ($fullyQualifiedTraits === []) {
            return false;
        }

        foreach ($fullyQualifiedTraits as $trait) {
            if (! $trait instanceof FullyQualified) {
                return false;
            }

            /** @var string $traitName */
            $traitName = $this->nodeNameResolver->getName($trait);
            $isTraitFound = (bool) $this->nodeRepository->findTrait($traitName);

            if (! $isTraitFound) {
                return true;
            }
        }

        return false;
    }
}
