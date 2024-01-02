<?php

declare(strict_types=1);

namespace Rector\Naming\ExpectedNameResolver;

use PhpParser\Node\Param;
use PHPStan\Type\ObjectType;
use Rector\Naming\Naming\PropertyNaming;
use Rector\Naming\ValueObject\ExpectedName;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\StaticTypeMapper\StaticTypeMapper;

final readonly class MatchParamTypeExpectedNameResolver
{
    public function __construct(
        private StaticTypeMapper $staticTypeMapper,
        private PropertyNaming $propertyNaming,
        private NodeTypeResolver $nodeTypeResolver,
    ) {
    }

    public function resolve(Param $param): ?string
    {
        // nothing to verify
        if ($param->type === null) {
            return null;
        }

        // include nullable too
        // skip date time + date time interface, as should be kept
        if ($this->nodeTypeResolver->isObjectType($param->type, new ObjectType('DateTimeInterface'))) {
            return null;
        }

        $staticType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($param->type);
        $expectedName = $this->propertyNaming->getExpectedNameFromType($staticType);
        if (! $expectedName instanceof ExpectedName) {
            return null;
        }

        return $expectedName->getName();
    }
}
