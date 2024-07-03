<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Analyser\Scope;
use PHPStan\Type\MixedType;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\NodeAnalyzer\ReturnTypeAnalyzer\StrictNativeFunctionReturnTypeAnalyzer;
use Rector\VendorLocker\NodeVendorLocker\ClassMethodReturnTypeOverrideGuard;

final readonly class AddReturnTypeFromStrictNativeCall
{
    public function __construct(
        private StaticTypeMapper $staticTypeMapper,
        private StrictNativeFunctionReturnTypeAnalyzer $strictNativeFunctionReturnTypeAnalyzer,
        private NodeTypeResolver $nodeTypeResolver,
        private TypeFactory $typeFactory,
        private ClassMethodReturnTypeOverrideGuard $classMethodReturnTypeOverrideGuard,
    ) {
    }

    /**
     * @template TFunctionLike as ClassMethod|Function_
     *
     * @param TFunctionLike $functionLike
     * @return TFunctionLike|null
     */
    public function add(ClassMethod|Function_ $functionLike, Scope $scope): ClassMethod|Function_|null
    {
        // already filled, skip
        if ($functionLike->returnType instanceof Node) {
            return null;
        }

        if ($functionLike instanceof ClassMethod && $this->classMethodReturnTypeOverrideGuard->shouldSkipClassMethod(
            $functionLike,
            $scope
        )) {
            return null;
        }

        $nativeCallLikes = $this->strictNativeFunctionReturnTypeAnalyzer->matchAlwaysReturnNativeCallLikes(
            $functionLike
        );
        if ($nativeCallLikes === null) {
            return null;
        }

        $callLikeTypes = [];
        foreach ($nativeCallLikes as $nativeCallLike) {
            $callLikeTypes[] = $this->nodeTypeResolver->getType($nativeCallLike);
        }

        $returnType = $this->typeFactory->createMixedPassedOrUnionTypeAndKeepConstant($callLikeTypes);
        if ($returnType instanceof MixedType) {
            return null;
        }

        $returnTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($returnType, TypeKind::RETURN);
        if (! $returnTypeNode instanceof Node) {
            return null;
        }

        $functionLike->returnType = $returnTypeNode;
        return $functionLike;
    }
}
