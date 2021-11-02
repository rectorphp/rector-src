<?php

declare(strict_types=1);

namespace Rector\Naming\ExpectedNameResolver;

use PhpParser\Node\Param;
use Rector\CodingStyle\ClassNameImport\UsedImportsResolver;
use Rector\Naming\Naming\PropertyNaming;
use Rector\Naming\ValueObject\ExpectedName;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\StaticTypeMapper\ValueObject\Type\AliasedObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;

final class MatchParamTypeExpectedNameResolver
{
    public function __construct(
        private StaticTypeMapper $staticTypeMapper,
        private PropertyNaming $propertyNaming,
        private UsedImportsResolver $usedImportsResolver
    ) {
    }

    public function resolve(Param $param): ?string
    {
        // nothing to verify
        if ($param->type === null) {
            return null;
        }

        $staticType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($param->type);

        if ($staticType instanceof FullyQualifiedObjectType) {
            $objectTypes = $this->usedImportsResolver->resolveForNode($param->type);
            $className = $staticType->getClassName();

            foreach ($objectTypes as $objectType) {
                if ($objectType instanceof AliasedObjectType && $className === $objectType->getFullyQualifiedName()) {
                    $staticType = $objectType;
                    break;
                }
            }
        }

        $expectedName = $this->propertyNaming->getExpectedNameFromType($staticType);
        if (! $expectedName instanceof ExpectedName) {
            return null;
        }

        return $expectedName->getName();
    }
}
