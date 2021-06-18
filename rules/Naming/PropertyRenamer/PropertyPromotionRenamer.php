<?php

declare(strict_types=1);

namespace Rector\Naming\PropertyRenamer;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
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
use Rector\NodeNameResolver\NodeNameResolver;

final class PropertyPromotionRenamer
{
    public function __construct(
        private PhpVersionProvider $phpVersionProvider,
        private MatchParamTypeExpectedNameResolver $matchParamTypeExpectedNameResolver,
        private ParamRenameFactory $paramRenameFactory,
        private PhpDocInfoFactory $phpDocInfoFactory,
        private ParamRenamer $paramRenamer,
        private PropertyFetchRenamer $propertyFetchRenamer,
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    public function renamePropertyPromotion(ClassLike $classLike): void
    {
        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::PROPERTY_PROMOTION)) {
            return;
        }

        $constructClassMethod = $classLike->getMethod(MethodName::CONSTRUCT);
        if (! $constructClassMethod instanceof ClassMethod) {
            return;
        }

        $desiredPropertyNames = [];
        foreach ($constructClassMethod->params as $key => $param) {
            if ($param->flags === 0) {
                continue;
            }

            // promoted property
            $desiredPropertyName = $this->matchParamTypeExpectedNameResolver->resolve($param);
            if ($desiredPropertyName === null) {
                continue;
            }

            if (in_array($desiredPropertyName, $desiredPropertyNames, true)) {
                return;
            }

            $desiredPropertyNames[$key] = $desiredPropertyName;
        }

        $this->renameParamVarName($classLike, $constructClassMethod, $desiredPropertyNames);
    }

    /**
     * @param string[] $desiredPropertyNames
     */
    private function renameParamVarName(
        ClassLike $classLike,
        ClassMethod $constructClassMethod,
        array $desiredPropertyNames
    ): void {
        $keys = array_keys($desiredPropertyNames);
        $params = $constructClassMethod->params;
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($constructClassMethod);

        foreach ($params as $key => $param) {
            if (! in_array($key, $keys, true)) {
                continue;
            }

            $currentParamName = $this->nodeNameResolver->getName($param);
            $desiredPropertyName = $desiredPropertyNames[$key];
            $this->propertyFetchRenamer->renamePropertyFetchesInClass(
                $classLike,
                $currentParamName,
                $desiredPropertyName
            );

            /** @var string $paramVarName */
            $paramVarName = $param->var->name;
            $this->renameParamDoc($phpDocInfo, $param, $paramVarName, $desiredPropertyName);
            $param->var->name = $desiredPropertyName;
        }
    }

    private function renameParamDoc(
        PhpDocInfo $phpDocInfo,
        Param $param,
        string $paramVarName,
        string $desiredPropertyName
    ): void {
        $paramTagValueNode = $phpDocInfo->getParamTagValueNodeByName($paramVarName);

        if (! $paramTagValueNode instanceof ParamTagValueNode) {
            return;
        }

        $paramRename = $this->paramRenameFactory->createFromResolvedExpectedName($param, $desiredPropertyName);
        if (! $paramRename instanceof ParamRename) {
            return;
        }

        $this->paramRenamer->rename($paramRename);
    }
}
