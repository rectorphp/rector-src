<?php

declare(strict_types=1);

namespace Rector\Naming\ExpectedNameResolver;

use PhpParser\Node\Param;
use PHPStan\Type\ObjectType;
use Rector\Naming\Naming\PropertyNaming;
use Rector\Naming\ValueObject\ExpectedName;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\StaticTypeMapper\StaticTypeMapper;

final class MatchParamTypeExpectedNameResolver
{
    public function __construct(
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly PropertyNaming $propertyNaming,
        private readonly NodeNameResolver $nodeNameResolver,
    ) {
    }

    public function resolve(Param $param): ?string
    {
        // nothing to verify
        if ($param->type === null) {
            return null;
        }

        $staticType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($param->type);

        // skip date time + date time interface, as should be kept
        if ($staticType instanceof ObjectType && $staticType->isInstanceOf('DateTimeInterface')->yes()) {
            if ($this->nodeNameResolver->isName($param, '*At')) {
                return null;
            }
        }

        $expectedName = $this->propertyNaming->getExpectedNameFromType($staticType);
        if (! $expectedName instanceof ExpectedName) {
            return null;
        }

        return $expectedName->getName();
    }
}
