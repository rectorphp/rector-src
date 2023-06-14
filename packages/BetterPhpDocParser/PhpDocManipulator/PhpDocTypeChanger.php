<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\PhpDocManipulator;

use PhpParser\Node\FunctionLike;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstFetchNode;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeNode;
use PHPStan\PhpDocParser\Ast\Type\ConstTypeNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NeverType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\Comment\CommentsMerger;
use Rector\BetterPhpDocParser\Guard\NewPhpDocFromPHPStanTypeGuard;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\ValueObject\Type\BracketsAwareIntersectionTypeNode;
use Rector\BetterPhpDocParser\ValueObject\Type\BracketsAwareUnionTypeNode;
use Rector\BetterPhpDocParser\ValueObject\Type\SpacingAwareArrayTypeNode;
use Rector\BetterPhpDocParser\ValueObject\Type\SpacingAwareCallableTypeNode;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\PhpDocParser\ParamPhpDocNodeFactory;

final class PhpDocTypeChanger
{
    /**
     * @var array<class-string<Node>>
     */
    private const ALLOWED_TYPES = [
        GenericTypeNode::class,
        SpacingAwareArrayTypeNode::class,
        SpacingAwareCallableTypeNode::class,
        ArrayShapeNode::class,
    ];

    /**
     * @var string[]
     */
    private const ALLOWED_IDENTIFIER_TYPENODE_TYPES = ['class-string'];

    public function __construct(
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly TypeComparator $typeComparator,
        private readonly ParamPhpDocNodeFactory $paramPhpDocNodeFactory,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly CommentsMerger $commentsMerger,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly NewPhpDocFromPHPStanTypeGuard $newPhpDocFromPHPStanTypeGuard,
        private readonly DocBlockUpdater $docBlockUpdater
    ) {
    }

    public function changeVarType(Stmt $stmt, PhpDocInfo $phpDocInfo, Type $newType): void
    {
        // better skip, could crash hard
        if ($phpDocInfo->hasInvalidTag('@var')) {
            return;
        }

        // make sure the tags are not identical, e.g imported class vs FQN class
        if ($this->typeComparator->areTypesEqual($phpDocInfo->getVarType(), $newType)) {
            return;
        }

        // prevent existing type override by mixed
        if (! $phpDocInfo->getVarType() instanceof MixedType && $newType instanceof ConstantArrayType && $newType->getItemType() instanceof NeverType) {
            return;
        }

        if (! $this->newPhpDocFromPHPStanTypeGuard->isLegal($newType)) {
            return;
        }

        // override existing type
        $newPHPStanPhpDocTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($newType);

        $currentVarTagValueNode = $phpDocInfo->getVarTagValueNode();
        if ($currentVarTagValueNode instanceof VarTagValueNode) {
            // only change type
            $currentVarTagValueNode->type = $newPHPStanPhpDocTypeNode;
            $phpDocInfo->markAsChanged();
        } else {
            // add completely new one
            $varTagValueNode = new VarTagValueNode($newPHPStanPhpDocTypeNode, '', '');

            $phpDocInfo->addTagValueNode($varTagValueNode);
        }

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($stmt);
    }

    public function changeReturnType(FunctionLike $functionLike, PhpDocInfo $phpDocInfo, Type $newType): bool
    {
        // better not touch this, can crash
        if ($phpDocInfo->hasInvalidTag('@return')) {
            return false;
        }

        // make sure the tags are not identical, e.g imported class vs FQN class
        if ($this->typeComparator->areTypesEqual($phpDocInfo->getReturnType(), $newType)) {
            return false;
        }

        if (! $this->newPhpDocFromPHPStanTypeGuard->isLegal($newType)) {
            return false;
        }

        // override existing type
        $newPHPStanPhpDocTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($newType);

        $currentReturnTagValueNode = $phpDocInfo->getReturnTagValue();

        if ($currentReturnTagValueNode instanceof ReturnTagValueNode) {
            // only change type
            $currentReturnTagValueNode->type = $newPHPStanPhpDocTypeNode;
            $phpDocInfo->markAsChanged();
        } else {
            // add completely new one
            $returnTagValueNode = new ReturnTagValueNode($newPHPStanPhpDocTypeNode, '');
            $phpDocInfo->addTagValueNode($returnTagValueNode);
        }

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($functionLike);

        return true;
    }

    public function changeParamType(
        FunctionLike $functionLike,
        PhpDocInfo $phpDocInfo,
        Type $newType,
        Param $param,
        string $paramName
    ): void {
        // better skip, could crash hard
        if ($phpDocInfo->hasInvalidTag('@param')) {
            return;
        }

        if (! $this->newPhpDocFromPHPStanTypeGuard->isLegal($newType)) {
            return;
        }

        $phpDocTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($newType);
        $paramTagValueNode = $phpDocInfo->getParamTagValueByName($paramName);

        // override existing type
        if ($paramTagValueNode instanceof ParamTagValueNode) {
            // already set
            $currentType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType(
                $paramTagValueNode->type,
                $param
            );

            // avoid overriding better type
            if ($this->typeComparator->isSubtype($currentType, $newType)) {
                return;
            }

            if ($this->typeComparator->areTypesEqual($currentType, $newType)) {
                return;
            }

            $paramTagValueNode->type = $phpDocTypeNode;
            $phpDocInfo->markAsChanged();
        } else {
            $paramTagValueNode = $this->paramPhpDocNodeFactory->create($phpDocTypeNode, $param);
            $phpDocInfo->addTagValueNode($paramTagValueNode);
        }

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($functionLike);
    }

    public function isAllowed(TypeNode $typeNode): bool
    {
        if ($typeNode instanceof BracketsAwareUnionTypeNode || $typeNode instanceof BracketsAwareIntersectionTypeNode) {
            foreach ($typeNode->types as $type) {
                if ($this->isAllowed($type)) {
                    return true;
                }
            }
        }

        if ($typeNode instanceof ConstTypeNode && $typeNode->constExpr instanceof ConstFetchNode) {
            return true;
        }

        if (in_array($typeNode::class, self::ALLOWED_TYPES, true)) {
            return true;
        }

        if (! $typeNode instanceof IdentifierTypeNode) {
            return false;
        }

        return in_array((string) $typeNode, self::ALLOWED_IDENTIFIER_TYPENODE_TYPES, true);
    }

    public function copyPropertyDocToParam(ClassMethod $classMethod, Property $property, Param $param): void
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($property);
        if (! $phpDocInfo instanceof PhpDocInfo) {
            return;
        }

        $varTagValueNode = $phpDocInfo->getVarTagValueNode();
        if (! $varTagValueNode instanceof VarTagValueNode) {
            $this->processKeepComments($classMethod, $property, $param);
            return;
        }

        if ($varTagValueNode->description !== '') {
            return;
        }

        $paramVarName = $this->nodeNameResolver->getName($param->var);
        if (! $this->isAllowed($varTagValueNode->type)) {
            return;
        }

        if (! is_string($paramVarName)) {
            return;
        }

        $phpDocInfo->removeByType(VarTagValueNode::class);
        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($property);

        $param->setAttribute(AttributeKey::PHP_DOC_INFO, $phpDocInfo);

        $phpDocInfo = $classMethod->getAttribute(AttributeKey::PHP_DOC_INFO);
        $paramType = $this->staticTypeMapper->mapPHPStanPhpDocTypeToPHPStanType($varTagValueNode, $property);

        $this->changeParamType($classMethod, $phpDocInfo, $paramType, $param, $paramVarName);
        $this->processKeepComments($classMethod, $property, $param);
    }

    /**
     * @api doctrine
     */
    public function changeVarTypeNode(Stmt $stmt, PhpDocInfo $phpDocInfo, TypeNode $typeNode): void
    {
        // add completely new one
        $varTagValueNode = new VarTagValueNode($typeNode, '', '');
        $phpDocInfo->addTagValueNode($varTagValueNode);

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($stmt);
    }

    private function processKeepComments(ClassMethod $classMethod, Property $property, Param $param): void
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($param);
        $varTagValueNode = $phpDocInfo->getVarTagValueNode();

        $toBeRemoved = ! $varTagValueNode instanceof VarTagValueNode;
        $this->commentsMerger->keepComments($param, [$property]);

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($classMethod);

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($param);
        $varTagValueNode = $phpDocInfo->getVarTagValueNode();
        if (! $toBeRemoved) {
            return;
        }

        if (! $varTagValueNode instanceof VarTagValueNode) {
            return;
        }

        if ($varTagValueNode->description !== '') {
            return;
        }

        $phpDocInfo->removeByType(VarTagValueNode::class);

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($classMethod);
    }
}
