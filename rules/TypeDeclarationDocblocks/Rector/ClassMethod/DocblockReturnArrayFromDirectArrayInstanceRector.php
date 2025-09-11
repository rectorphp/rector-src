<?php

declare(strict_types=1);

namespace Rector\TypeDeclarationDocblocks\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\Type\BooleanType;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\StringType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\DocblockReturnArrayFromDirectArrayInstanceRectorTest
 */
final class DocblockReturnArrayFromDirectArrayInstanceRector extends AbstractRector
{
    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly StaticTypeMapper $staticTypeMapper
    ) {
    }

    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add @return array docblock based on direct single level direct return of []', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function getItems(): array
    {
        return [
            'hey' => 'now',
        ];
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @return array<string, string>
     */
    public function getItems(): array
    {
        return [
            'hey' => 'now',
        ];
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->returnType instanceof Node) {
            return null;
        }

        if (! $this->isName($node->returnType, 'array')) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        // return tag is already given
        if ($phpDocInfo->getReturnTagValue() instanceof ReturnTagValueNode) {
            return null;
        }

        if (count($node->stmts) !== 1) {
            return null;
        }

        $soleReturn = $node->stmts[0];
        if (! $soleReturn instanceof Return_) {
            return null;
        }

        if (! $soleReturn->expr instanceof Array_) {
            return null;
        }

        // resolve simple type
        $returnedType = $this->getType($soleReturn->expr);

        if (! $returnedType instanceof ConstantArrayType) {
            return null;
        }

        $genericKeyType = $this->constantToGenericType($returnedType->getKeyType());
        $genericItemType = $this->constantToGenericType($returnedType->getItemType());

        $genericTypeNode = $this->createArrayGenericTypeNode($genericKeyType, $genericItemType);

        $returnTagValueNode = new ReturnTagValueNode($genericTypeNode, '');
        $phpDocInfo->addTagValueNode($returnTagValueNode);

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

        return $node;
    }

    /**
     * covers constant types too and makes them more generic
     */
    private function constantToGenericType(\PHPStan\Type\Type $type): \PHPStan\Type\Type
    {
        if ($type instanceof StringType) {
            return new StringType();
        }

        if ($type instanceof IntegerType) {
            return new IntegerType();
        }

        if ($type instanceof BooleanType) {
            return new BooleanType();
        }

        if ($type instanceof FloatType) {
            return new FloatType();
        }

        // unclear
        return new MixedType();
    }

    private function createArrayGenericTypeNode(
        \PHPStan\Type\Type $keyType,
        \PHPStan\Type\Type $itemType
    ): GenericTypeNode {
        $keyDocTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($keyType);
        $itemDocTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($itemType);

        return new GenericTypeNode(new IdentifierTypeNode('array'), [$keyDocTypeNode, $itemDocTypeNode]);
    }
}
