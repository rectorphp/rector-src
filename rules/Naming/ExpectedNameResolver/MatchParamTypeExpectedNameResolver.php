<?php

declare(strict_types=1);

namespace Rector\Naming\ExpectedNameResolver;

use PhpParser\Node;
use PhpParser\Node\Param;
use Rector\Naming\Naming\PropertyNaming;
use Rector\Naming\ValueObject\ExpectedName;
use Rector\StaticTypeMapper\StaticTypeMapper;

final readonly class MatchParamTypeExpectedNameResolver
{
    public function __construct(
        private StaticTypeMapper $staticTypeMapper,
        private PropertyNaming $propertyNaming,
    ) {
    }

    public function resolve(Param $param): ?string
    {
        // nothing to verify
        if (! $param->type instanceof Node) {
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
