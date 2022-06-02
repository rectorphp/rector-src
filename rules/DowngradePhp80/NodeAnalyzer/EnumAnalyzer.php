<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\NodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\EnumCase;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\PhpParser\AstResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;

final class EnumAnalyzer
{
    public function __construct(
        private readonly AstResolver $astResolver,
        private readonly NodeTypeResolver $nodeTypeResolver
    ) {
    }

    public function resolveType(ClassReflection $classReflection): ?Identifier
    {
        $class = $this->astResolver->resolveClassFromClassReflection($classReflection, $classReflection->getName());
        if (! $class instanceof Enum_) {
            throw new ShouldNotHappenException();
        }

        $scalarType = $class->scalarType;
        if ($scalarType instanceof Identifier) {
            // can be only int or string
            return $scalarType;
        }

        $enumExprTypes = $this->resolveEnumExprTypes($class);

        $enumExprTypeClasses = [];

        foreach ($enumExprTypes as $enumExprType) {
            $enumExprTypeClasses[] = $enumExprType::class;
        }

        $uniqueEnumExprTypeClasses = array_unique($enumExprTypeClasses);
        if (count($uniqueEnumExprTypeClasses) === 1) {
            $uniqueEnumExprTypeClass = $uniqueEnumExprTypeClasses[0];
            if (is_a($uniqueEnumExprTypeClass, StringType::class, true)) {
                return new Identifier('string');
            }

            if (is_a($uniqueEnumExprTypeClass, IntegerType::class, true)) {
                return new Identifier('int');
            }

            if (is_a($uniqueEnumExprTypeClass, FloatType::class, true)) {
                return new Identifier('float');
            }
        }

        // unknown or multiple types
        return null;
    }

    /**
     * @return Type[]
     */
    private function resolveEnumExprTypes(Enum_ $enum): array
    {
        $enumExprTypes = [];

        foreach ($enum->stmts as $classStmt) {
            if (! $classStmt instanceof EnumCase) {
                continue;
            }

            $enumExprTypes[] = $this->resolveEnumCaseType($classStmt);
        }

        return $enumExprTypes;
    }

    private function resolveEnumCaseType(EnumCase $enumCase): Type
    {
        $classExpr = $enumCase->expr;
        if ($classExpr instanceof Expr) {
            return $this->nodeTypeResolver->getType($classExpr);
        }

        // in case of no value, fallback to string type
        return new StringType();
    }
}
