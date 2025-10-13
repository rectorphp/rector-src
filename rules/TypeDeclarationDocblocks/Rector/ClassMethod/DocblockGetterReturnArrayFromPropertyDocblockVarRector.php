<?php

declare(strict_types=1);

namespace Rector\TypeDeclarationDocblocks\Rector\ClassMethod;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayTypeNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\Type\Type;
use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\UnionType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Rector\AbstractRector;
use Rector\TypeDeclarationDocblocks\NodeDocblockTypeDecorator;
use Rector\TypeDeclarationDocblocks\TagNodeAnalyzer\UsefulArrayTagNodeAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockGetterReturnArrayFromPropertyDocblockVarRector\DocblockGetterReturnArrayFromPropertyDocblockVarRectorTest
 */
final class DocblockGetterReturnArrayFromPropertyDocblockVarRector extends AbstractRector
{
    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly UsefulArrayTagNodeAnalyzer $usefulArrayTagNodeAnalyzer,
        private readonly NodeDocblockTypeDecorator $nodeDocblockTypeDecorator
    ) {
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add @return array docblock to a getter method based on @var of the property', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @var int[]
     */
    private array $items;

    public function getItems(): array
    {
        return $this->items;
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @var int[]
     */
    private array $items;

    /**
     * @return int[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->isAnonymous()) {
            return null;
        }

        $hasChanged = false;
        foreach ($node->getMethods() as $classMethod) {
            if (! $classMethod->returnType instanceof Node) {
                continue;
            }

            if (! $this->isName($classMethod->returnType, 'array')) {
                continue;
            }

            $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);
            if ($this->usefulArrayTagNodeAnalyzer->isUsefulArrayTag($phpDocInfo->getReturnTagValue())) {
                continue;
            }

            // @todo add promoted proeprty
            $property = $this->matchReturnLocalPropertyFetch($classMethod, $node);
            if (! $property instanceof Property) {
                continue;
            }

            $propertyDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($property);

            $varTagValueNode = $propertyDocInfo->getVarTagValueNode();

            if (! $varTagValueNode instanceof VarTagValueNode) {
                continue;
            }

            // is type useful?
            if (! $varTagValueNode->type instanceof GenericTypeNode && ! $varTagValueNode->type instanceof ArrayTypeNode) {
                continue;
            }

            if (! $this->nodeDocblockTypeDecorator->decorateGenericIterableReturnType(
                $varTagValueNode->type,
                $phpDocInfo,
                $classMethod
            )) {
                continue;
            }

            $hasChanged = true;
        }

        if (! $hasChanged) {
            return null;
        }

        return $node;
    }

    private function matchReturnLocalPropertyFetch(ClassMethod $classMethod, Class_ $class): ?Property
    {
        // we need exactly one statement of return
        if ($classMethod->stmts === null || count($classMethod->stmts) !== 1) {
            return null;
        }

        $onlyStmt = $classMethod->stmts[0];
        if (! $onlyStmt instanceof Return_) {
            return null;
        }

        if (! $onlyStmt->expr instanceof PropertyFetch) {
            return null;
        }

        $propertyFetch = $onlyStmt->expr;
        if (! $this->isName($propertyFetch->var, 'this')) {
            return null;
        }

        $propertyName = $this->getName($propertyFetch->name);
        if (! is_string($propertyName)) {
            return null;
        }

        return $class->getProperty($propertyName);
    }

    private function isUsefulType(Type $type): bool
    {
        if ($type instanceof UnionType) {
            return false;
        }

        if (! $type instanceof ArrayType) {
            return true;
        }

        if (! $type->getKeyType() instanceof MixedType) {
            return true;
        }

        return ! $type->getItemType() instanceof MixedType;
    }
}
