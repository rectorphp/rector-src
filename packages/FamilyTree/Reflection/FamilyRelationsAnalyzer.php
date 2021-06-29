<?php

declare(strict_types=1);

namespace Rector\FamilyTree\Reflection;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\UnionType as PhpParserUnionType;
use PhpParser\Parser;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\NullType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Php74\ValueObject\PropertyType;
use Rector\PHPStanStaticTypeMapper\ValueObject\TypeKind;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Symplify\PackageBuilder\Reflection\PrivatesAccessor;
use Symplify\SmartFileSystem\SmartFileSystem;

final class FamilyRelationsAnalyzer
{
    public function __construct(
        private ReflectionProvider $reflectionProvider,
        private PrivatesAccessor $privatesAccessor,
        private NodeNameResolver $nodeNameResolver,
        private SmartFileSystem $smartFileSystem,
        private BetterNodeFinder $betterNodeFinder,
        private StaticTypeMapper $staticTypeMapper,
        private Parser $parser
    ) {
    }

    /**
     * @return ClassReflection[]
     */
    public function getChildrenOfClassReflection(ClassReflection $desiredClassReflection): array
    {
        /** @var ClassReflection[] $classReflections */
        $classReflections = $this->privatesAccessor->getPrivateProperty($this->reflectionProvider, 'classes');

        $childrenClassReflections = [];

        foreach ($classReflections as $classReflection) {
            if (! $classReflection->isSubclassOf($desiredClassReflection->getName())) {
                continue;
            }

            $childrenClassReflections[] = $classReflection;
        }

        return $childrenClassReflections;
    }

    /**
     * @param Name|NullableType|PhpParserUnionType|null $propertyTypeNode
     */
    public function getPossibleUnionPropertyType(
        Property $property,
        Type $varType,
        ?Scope $scope,
        ?Node $propertyTypeNode
    ): PropertyType {
        if ($varType instanceof UnionType) {
            return new PropertyType($varType, $propertyTypeNode);
        }

        if (! $scope instanceof Scope) {
            return new PropertyType($varType, $propertyTypeNode);
        }

        /** @var ClassReflection $classReflection */
        $classReflection = $scope->getClassReflection();
        $ancestors = $classReflection->getAncestors();
        $propertyName = $this->nodeNameResolver->getName($property);

        $kindPropertyFetch = $property->isStatic()
            ? StaticPropertyFetch::class
            : PropertyFetch::class;

        $className = $property->getAttribute(AttributeKey::CLASS_NAME);
        foreach ($ancestors as $ancestor) {
            $ancestorName = $ancestor->getName();
            if ($ancestorName === $className) {
                continue;
            }

            $fileName = $ancestor->getFileName();
            if ($fileName === false) {
                continue;
            }
            $fileContent = $this->smartFileSystem->readFile($fileName);
            $nodes = $this->parser->parse($fileContent);

            if ($ancestor->isSubclassOf('PHPUnit\Framework\TestCase')) {
                continue;
            }

            if ($nodes === null) {
                continue;
            }

            if ($this->isFilled($nodes, $propertyName, $kindPropertyFetch)) {
                $varType = new UnionType([$varType, new NullType()]);
                $propertyTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
                    $varType,
                    TypeKind::KIND_PROPERTY
                );

                return new PropertyType($varType, $propertyTypeNode);
            }
        }

        return new PropertyType($varType, $propertyTypeNode);
    }

    /**
     * @param Stmt[] $nodes
     */
    private function isFilled(array $nodes, string $propertyName, string $kindPropertyFetch): bool
    {
        return (bool) $this->betterNodeFinder->findFirst($nodes, function (Node $n) use (
            $propertyName,
            $kindPropertyFetch
        ): bool {
            if (! $n instanceof ClassMethod) {
                return false;
            }

            if ($this->nodeNameResolver->isNames($n->name, ['autowire', 'setUp'])) {
                return false;
            }

            return (bool) $this->betterNodeFinder->findFirst((array) $n->stmts, function (Node $n2) use (
                $propertyName,
                $kindPropertyFetch
            ): bool {
                if (! $n2 instanceof Assign) {
                    return false;
                }

                return $kindPropertyFetch === $n2->var::class && $this->nodeNameResolver->isName(
                    $n2->var,
                    $propertyName
                );
            });
        });
    }
}
