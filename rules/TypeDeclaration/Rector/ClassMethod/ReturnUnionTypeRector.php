<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Analyser\Scope;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\UnionType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\ValueObject\Type\BracketsAwareUnionTypeNode;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\PHPStanStaticTypeMapper\TypeMapper\UnionTypeMapper;
use Rector\Rector\AbstractScopeAwareRector;
use Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VendorLocker\NodeVendorLocker\ClassMethodReturnTypeOverrideGuard;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\ReturnUnionTypeRectorTest
 */
final class ReturnUnionTypeRector extends AbstractScopeAwareRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ReturnTypeInferer $returnTypeInferer,
        private readonly ClassMethodReturnTypeOverrideGuard $classMethodReturnTypeOverrideGuard,
        private readonly UnionTypeMapper $unionTypeMapper,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly DocBlockUpdater $docBlockUpdater
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add union return type', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function getData()
    {
        if (rand(0, 1)) {
            return null;
        }

        if (rand(0, 1)) {
            return new DateTime('now');
        }

        return new stdClass;
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function getData(): null|\DateTime|\stdClass
    {
        if (rand(0, 1)) {
            return null;
        }

        if (rand(0, 1)) {
            return new DateTime('now');
        }

        return new stdClass;
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class, Function_::class];
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::NULLABLE_TYPE;
    }

    /**
     * @param ClassMethod|Function_ $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        if ($node->stmts === null) {
            return null;
        }

        if ($node->returnType instanceof Node) {
            return null;
        }

        if ($node instanceof ClassMethod && $this->classMethodReturnTypeOverrideGuard->shouldSkipClassMethod(
            $node,
            $scope
        )) {
            return null;
        }

        $inferReturnType = $this->returnTypeInferer->inferFunctionLike($node);
        if (! $inferReturnType instanceof UnionType) {
            return null;
        }

        $returnType = $this->unionTypeMapper->mapToPhpParserNode($inferReturnType, TypeKind::RETURN);
        if (! $returnType instanceof Node) {
            return null;
        }

        $this->mapStandaloneSubType($node, $inferReturnType);

        $node->returnType = $returnType;
        return $node;
    }

    private function mapStandaloneSubType(ClassMethod|Function_ $node, UnionType $unionType): void
    {
        $value = null;

        foreach ($unionType->getTypes() as $type) {
            if ($type instanceof ConstantBooleanType) {
                $value = $type->getValue() ? 'true' : 'false';
                break;
            }
        }

        if ($value === null) {
            return;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        $returnType = $phpDocInfo->getReturnTagValue();

        if (! $returnType instanceof ReturnTagValueNode) {
            return;
        }

        if (! $returnType->type instanceof BracketsAwareUnionTypeNode) {
            return;
        }

        foreach ($returnType->type->types as $key => $type) {
            if ($type instanceof IdentifierTypeNode && $type->__toString() === 'bool') {
                $returnType->type->types[$key] = new IdentifierTypeNode($value);
                $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

                break;
            }
        }
    }
}
