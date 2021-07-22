<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\NodeFinder;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Trait_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\PhpParser\AstResolver;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\Core\ValueObject\Application\File;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeScopeAndMetadataDecorator;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;
use PhpParser\Parser;

final class PropertyFetchFinder
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private NodeNameResolver $nodeNameResolver,
        private ReflectionProvider $reflectionProvider,
        private ReflectionResolver $reflectionResolver,
        private SmartFileSystem $smartFileSystem,
        private Parser $parser,
        private NodeScopeAndMetadataDecorator $nodeScopeAndMetadataDecorator,
        private AstResolver $astResolver
    ) {
    }

    /**
     * @return PropertyFetch[]|StaticPropertyFetch[]
     */
    public function findPrivatePropertyFetches(Property | Param $propertyOrPromotedParam): array
    {
        $classLike = $propertyOrPromotedParam->getAttribute(AttributeKey::CLASS_NODE);
        if (! $classLike instanceof Class_) {
            return [];
        }

        $propertyName = $this->resolvePropertyName($propertyOrPromotedParam);
        if ($propertyName === null) {
            return [];
        }

        $className       = $this->nodeNameResolver->getName($classLike);
        $classReflection = $this->reflectionProvider->getClass($className);

        if (! $classReflection instanceof ClassReflection) {
            return [];
        }

        $classLikes = $classReflection->getTraits(true);
        $nodes      = $classLike;

        foreach ($classLikes as $classLike) {
            $fileName = $classLike->getFileName();
            if (! $fileName) {
                continue;
            }

            $fileContent = $this->smartFileSystem->readFile($fileName);
            $parsedNodes = $this->parser->parse($fileContent);

            $smartFileInfo = new SmartFileInfo($fileName);
            $file = new File($smartFileInfo, $smartFileInfo->getContents());

            $allNodes = $this->nodeScopeAndMetadataDecorator->decorateNodesFromFile($file, $parsedNodes);
            $traitName = $this->nodeNameResolver->getName($classLike);
            $traits   = $this->betterNodeFinder->findFirst($allNodes, function (Node $node) use ($traitName) {
                return $node instanceof Trait_ && $this->nodeNameResolver->isName($node, $traitName);
            });

            dump_node($traits);die;

            foreach ($allNodes as $node) {
                dump($node::class);
                if ($node instanceof Trait_) {
                    $nodes[] = $node; die;
                }
            }
        }



        /** @var PropertyFetch[]|StaticPropertyFetch[] $propertyFetches */
        return $this->betterNodeFinder->find($nodes, function (Node $node) use (
            $propertyName
        ): bool {
            // property + static fetch
            if (! $node instanceof PropertyFetch && ! $node instanceof StaticPropertyFetch) {
                return false;
            }

            // is it the name match?
            return $this->nodeNameResolver->isName($node, $propertyName);
        });
    }

    /**
     * @return PropertyFetch[]
     */
    public function findLocalPropertyFetchesByName(Class_ $class, string $paramName): array
    {
        /** @var PropertyFetch[] $propertyFetches */
        $propertyFetches = $this->betterNodeFinder->findInstanceOf($class, PropertyFetch::class);

        $foundPropertyFetches = [];

        foreach ($propertyFetches as $propertyFetch) {
            if (! $this->nodeNameResolver->isName($propertyFetch->var, 'this')) {
                continue;
            }

            if (! $this->nodeNameResolver->isName($propertyFetch->name, $paramName)) {
                continue;
            }

            $foundPropertyFetches[] = $propertyFetch;
        }

        return $foundPropertyFetches;
    }

    private function resolvePropertyName(Property | Param $propertyOrPromotedParam): ?string
    {
        if ($propertyOrPromotedParam instanceof Property) {
            return $this->nodeNameResolver->getName($propertyOrPromotedParam->props[0]);
        }

        return $this->nodeNameResolver->getName($propertyOrPromotedParam->var);
    }
}
