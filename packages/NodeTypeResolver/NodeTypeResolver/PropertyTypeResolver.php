<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\NodeTypeResolver;

use PhpParser\Node;
use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Core\PhpParser\NodeFinder\PropertyFetchFinder;
use Rector\NodeTypeResolver\Contract\NodeTypeResolverInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @see \Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\PropertyTypeResolver\PropertyTypeResolverTest
 */
final class PropertyTypeResolver implements NodeTypeResolverInterface
{
    private PropertyFetchFinder $propertyFetchFinder;

    private PhpDocInfoFactory $phpDocInfoFactory;

    #[Required]
    public function autowirePropertyTypeResolver(
        PropertyFetchFinder $propertyFetchFinder,
        PhpDocInfoFactory $phpDocInfoFactory
    ): void {
        $this->propertyFetchFinder = $propertyFetchFinder;
        $this->phpDocInfoFactory = $phpDocInfoFactory;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeClasses(): array
    {
        return [Property::class];
    }

    /**
     * @param Property $node
     */
    public function resolve(Node $node): Type
    {
        $class = $node->getAttribute(AttributeKey::CLASS_NODE);

        $propertyName = $node->props[0]->name->toString();
        $localPropertyFetches = $this->propertyFetchFinder->findLocalPropertyFetchesByName($class, $propertyName);

        foreach ($localPropertyFetches as $localPropertyFetch) {
            $scope = $localPropertyFetch->getAttribute(AttributeKey::SCOPE);
            if ($scope instanceof Scope) {
                return $scope->getType($localPropertyFetch);
            }
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if ($phpDocInfo instanceof PhpDocInfo) {
            return $phpDocInfo->getVarType();
        }

        return new MixedType();
    }
}
