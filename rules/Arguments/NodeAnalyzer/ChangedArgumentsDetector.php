<?php

declare(strict_types=1);

namespace Rector\Arguments\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Param;
use PHPStan\Type\Type;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\StaticTypeMapper\StaticTypeMapper;

final readonly class ChangedArgumentsDetector
{
    public function __construct(
        private ValueResolver $valueResolver,
        private StaticTypeMapper $staticTypeMapper,
        private TypeComparator $typeComparator
    ) {
    }

    public function isDefaultValueChanged(Param $param, mixed $value): bool
    {
        if (! $param->default instanceof Expr) {
            return false;
        }

        return ! $this->valueResolver->isValue($param->default, $value);
    }

    public function isTypeChanged(Param $param, ?Type $newType): bool
    {
        if (! $param->type instanceof Node) {
            return false;
        }

        if (! $newType instanceof Type) {
            return true;
        }

        $currentParamType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($param->type);

        return ! $this->typeComparator->areTypesEqual($currentParamType, $newType);
    }
}
