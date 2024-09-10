<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\PhpDocParser;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\CallableType;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\IterableType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\StringType;
use Rector\BetterPhpDocParser\ValueObject\Type\BracketsAwareIntersectionTypeNode;
use Rector\BetterPhpDocParser\ValueObject\Type\BracketsAwareUnionTypeNode;
use Rector\StaticTypeMapper\Mapper\ScalarStringToTypeMapper;

final readonly class TypeExpressionFromVarTagResolver
{
    public function __construct(
        private ScalarStringToTypeMapper $scalarStringToTypeMapper
    ) {
    }

    public function resolveTypeExpressionFromVarTag(TypeNode $typeNode, Variable $variable): Expr|false
    {
        if ($typeNode instanceof IdentifierTypeNode) {
            $scalarType = $this->scalarStringToTypeMapper->mapScalarStringToType($typeNode->name);
            $scalarTypeFunction = $this->getScalarTypeFunction($scalarType::class);
            if ($scalarTypeFunction !== null) {
                $arg = new Arg($variable);
                return new FuncCall(new Name($scalarTypeFunction), [$arg]);
            }

            if ($scalarType instanceof NullType) {
                return new Identical($variable, new ConstFetch(new Name('null')));
            }

            if ($scalarType instanceof ConstantBooleanType) {
                return new Identical(
                    $variable,
                    new ConstFetch(new Name($scalarType->getValue() ? 'true' : 'false'))
                );
            }

            if ($scalarType instanceof MixedType && ! $scalarType->isExplicitMixed()) {
                return new Instanceof_($variable, new Name($typeNode->name));
            }
        } elseif ($typeNode instanceof NullableTypeNode) {
            $unionExpressions = [];
            $nullableTypeExpression = $this->resolveTypeExpressionFromVarTag($typeNode->type, $variable);
            if ($nullableTypeExpression === false) {
                return false;
            }

            $unionExpressions[] = $nullableTypeExpression;
            $nullExpression = $this->resolveTypeExpressionFromVarTag(new IdentifierTypeNode('null'), $variable);
            assert($nullExpression !== false);
            $unionExpressions[] = $nullExpression;
            return $this->generateOrExpression($unionExpressions);
        } elseif ($typeNode instanceof BracketsAwareUnionTypeNode) {
            $unionExpressions = [];
            foreach ($typeNode->types as $typeNode) {
                $unionExpression = $this->resolveTypeExpressionFromVarTag($typeNode, $variable);
                if ($unionExpression === false) {
                    return false;
                }

                $unionExpressions[] = $unionExpression;
            }

            return $this->generateOrExpression($unionExpressions);
        } elseif ($typeNode instanceof BracketsAwareIntersectionTypeNode) {
            $intersectionExpressions = [];
            foreach ($typeNode->types as $typeNode) {
                $intersectionExpression = $this->resolveTypeExpressionFromVarTag($typeNode, $variable);
                if ($intersectionExpression === false) {
                    return false;
                }

                $intersectionExpressions[] = $intersectionExpression;
            }

            return $this->generateAndExpression($intersectionExpressions);
        }

        return false;
    }

    /**
     * @param Expr[] $unionExpressions
     * @return BooleanOr
     */
    private function generateOrExpression(array $unionExpressions)
    {
        $booleanOr = new BooleanOr($unionExpressions[0], $unionExpressions[1]);
        if (count($unionExpressions) == 2) {
            return $booleanOr;
        }

        array_splice($unionExpressions, 0, 2, [$booleanOr]);
        return $this->generateOrExpression($unionExpressions);
    }

    /**
     * @param Expr[] $intersectionExpressions
     * @return BooleanAnd
     */
    private function generateAndExpression(array $intersectionExpressions)
    {
        $booleanAnd = new BooleanAnd($intersectionExpressions[0], $intersectionExpressions[1]);
        if (count($intersectionExpressions) == 2) {
            return $booleanAnd;
        }

        array_splice($intersectionExpressions, 0, 2, [$booleanAnd]);
        return $this->generateAndExpression($intersectionExpressions);
    }

    /**
     * @param class-string $className
     */
    private function getScalarTypeFunction(string $className): ?string
    {
        return match ($className) {
            IntegerType::class => 'is_int',
            BooleanType::class => 'is_bool',
            FloatType::class => 'is_float',
            StringType::class => 'is_string',
            ArrayType::class => 'is_array',
            CallableType::class => 'is_callable',
            ObjectWithoutClassType::class => 'is_object',
            IterableType::class => 'is_iterable',
            default => null,
        };
    }
}
