<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\TraitUse;
use Rector\NodeCollector\NodeCollector\NodeRepository;
use Rector\NodeNameResolver\NodeNameResolver;

final class ExternalFullyQualifiedAnalyzer
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private NodeRepository $nodeRepository
    ) {
    }

    /**
     * @param FullyQualified|FullyQualified[]|null $fullyQualifiedClassLikes
     */
    public function hasExternalClassOrInterface($fullyQualifiedClassLikes): bool
    {
        if ($fullyQualifiedClassLikes === []) {
            return false;
        }

        if ($fullyQualifiedClassLikes === null) {
            return false;
        }

        if ($fullyQualifiedClassLikes instanceof FullyQualified) {
            $fullyQualifiedClassLikes = [$fullyQualifiedClassLikes];
        }

        foreach ($fullyQualifiedClassLikes as $fullyQualifiedClassLike) {
            /** @var string $className */
            $className = $this->nodeNameResolver->getName($fullyQualifiedClassLike);
            $isClassFound = (bool) $this->nodeRepository->findClass($className);
            $isInterfaceFound = (bool) $this->nodeRepository->findInterface($className);
            if ($isClassFound) {
                continue;
            }
            if ($isInterfaceFound) {
                continue;
            }

            return true;
        }

        return false;
    }

    /**
     * @param TraitUse[] $traitUses
     */
    public function hasExternalTrait(array $traitUses): bool
    {
        if ($traitUses === []) {
            return false;
        }

        foreach ($traitUses as $traitUse) {
            $traits = $traitUse->traits;

            foreach ($traits as $trait) {
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
        }

        return false;
    }
}
