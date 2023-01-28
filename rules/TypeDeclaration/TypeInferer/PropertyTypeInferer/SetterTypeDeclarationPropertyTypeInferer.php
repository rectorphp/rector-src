<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\NodeAnalyzer\ClassMethodAndPropertyAnalyzer;

final class SetterTypeDeclarationPropertyTypeInferer
{
    public function __construct(
        private readonly ClassMethodAndPropertyAnalyzer $classMethodAndPropertyAnalyzer,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly StaticTypeMapper $staticTypeMapper,
    ) {
    }

    public function inferProperty(Property $property, Class_ $class): ?Type
    {
        /** @var string $propertyName */
        $propertyName = $this->nodeNameResolver->getName($property);

        foreach ($class->getMethods() as $classMethod) {
            if (! $this->classMethodAndPropertyAnalyzer->hasOnlyPropertyAssign($classMethod, $propertyName)) {
                continue;
            }

            $paramTypeNode = $classMethod->params[0]->type ?? null;
            if (! $paramTypeNode instanceof Node) {
                return null;
            }

            $paramType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($paramTypeNode);

            // let PhpDoc solve that later for more precise type
            if ($paramType->isArray()->yes()) {
                return new MixedType();
            }

            if (! $paramType instanceof MixedType) {
                return $paramType;
            }
        }

        return null;
    }
}
