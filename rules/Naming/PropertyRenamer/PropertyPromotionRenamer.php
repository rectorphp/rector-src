<?php

declare(strict_types=1);

namespace Rector\Naming\PropertyRenamer;

use PhpParser\Node\Expr\Error;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\ValueObject\MethodName;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Naming\ExpectedNameResolver\MatchParamTypeExpectedNameResolver;
use Rector\Naming\ParamRenamer\ParamRenamer;
use Rector\Naming\ValueObject\ParamRename;
use Rector\Naming\ValueObjectFactory\ParamRenameFactory;
use Rector\Naming\VariableRenamer;
use Rector\NodeNameResolver\NodeNameResolver;

final class PropertyPromotionRenamer
{
    public function __construct(
        private readonly PhpVersionProvider $phpVersionProvider,
        private readonly MatchParamTypeExpectedNameResolver $matchParamTypeExpectedNameResolver,
        private readonly ParamRenameFactory $paramRenameFactory,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly ParamRenamer $paramRenamer,
        private readonly PropertyFetchRenamer $propertyFetchRenamer,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly VariableRenamer $variableRenamer
    ) {
    }

    public function renamePropertyPromotion(Class_|Interface_ $classLike): bool
    {
        $hasChanged = false;

        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::PROPERTY_PROMOTION)) {
            return false;
        }

        $constructClassMethod = $classLike->getMethod(MethodName::CONSTRUCT);
        if (! $constructClassMethod instanceof ClassMethod) {
            return false;
        }

        // resolve possible and existing param names
        $blockingParamNames = $this->resolveBlockingParamNames($constructClassMethod);

        foreach ($constructClassMethod->params as $param) {
            if ($param->flags === 0) {
                continue;
            }

            // promoted property
            $desiredPropertyName = $this->matchParamTypeExpectedNameResolver->resolve($param);
            if ($desiredPropertyName === null) {
                continue;
            }

            if (in_array($desiredPropertyName, $blockingParamNames, true)) {
                continue;
            }

            $currentParamName = $this->nodeNameResolver->getName($param);
            if ($this->isNameSuffixed($currentParamName, $desiredPropertyName)) {
                continue;
            }

            $this->renameParamVarNameAndVariableUsage($classLike, $constructClassMethod, $desiredPropertyName, $param);
            $hasChanged = true;
        }

        return $hasChanged;
    }

    private function renameParamVarNameAndVariableUsage(
        ClassLike $classLike,
        ClassMethod $classMethod,
        string $desiredPropertyName,
        Param $param
    ): void {
        if ($param->var instanceof Error) {
            return;
        }

        $classMethodPhpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);

        $currentParamName = $this->nodeNameResolver->getName($param);

        $this->propertyFetchRenamer->renamePropertyFetchesInClass(
            $classLike,
            $currentParamName,
            $desiredPropertyName
        );

        /** @var string $paramVarName */
        $paramVarName = $param->var->name;
        $this->renameParamDoc($classMethodPhpDocInfo, $classMethod, $param, $paramVarName, $desiredPropertyName);
        $param->var = new Variable($desiredPropertyName);

        $this->variableRenamer->renameVariableInFunctionLike($classMethod, $paramVarName, $desiredPropertyName);
    }

    private function renameParamDoc(
        PhpDocInfo $phpDocInfo,
        ClassMethod $classMethod,
        Param $param,
        string $paramVarName,
        string $desiredPropertyName
    ): void {
        $paramTagValueNode = $phpDocInfo->getParamTagValueByName($paramVarName);
        if (! $paramTagValueNode instanceof ParamTagValueNode) {
            return;
        }

        $paramRename = $this->paramRenameFactory->createFromResolvedExpectedName(
            $classMethod,
            $param,
            $desiredPropertyName
        );
        if (! $paramRename instanceof ParamRename) {
            return;
        }

        $this->paramRenamer->rename($paramRename);
    }

    /**
     * Sometimes the bare type is not enough.
     * This allows prefixing type in variable names, e.g. "Type $firstType"
     */
    private function isNameSuffixed(string $currentParamName, string $desiredPropertyName): bool
    {
        $currentNameLowercased = strtolower($currentParamName);
        $expectedNameLowercased = strtolower($desiredPropertyName);

        return str_ends_with($currentNameLowercased, $expectedNameLowercased);
    }

    /**
     * @return int[]|string[]
     */
    private function resolveBlockingParamNames(ClassMethod $classMethod): array
    {
        $futureParamNames = [];
        foreach ($classMethod->params as $param) {
            $futureParamName = $this->matchParamTypeExpectedNameResolver->resolve($param);
            if ($futureParamName === null) {
                continue;
            }

            $futureParamNames[] = $futureParamName;
        }

        // remove null values
        $futureParamNames = array_filter($futureParamNames);
        if ($futureParamNames === []) {
            return [];
        }

        // resolve duplicated names
        $blockingParamNames = [];

        $valuesToCount = array_count_values($futureParamNames);
        foreach ($valuesToCount as $value => $count) {
            if ($count < 2) {
                continue;
            }

            $blockingParamNames[] = $value;
        }

        return $blockingParamNames;
    }
}
