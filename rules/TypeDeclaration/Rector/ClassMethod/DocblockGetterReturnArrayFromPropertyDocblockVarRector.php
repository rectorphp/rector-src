<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\DocblockGetterReturnArrayFromPropertyDocblockVarRector\DocblockGetterReturnArrayFromPropertyDocblockVarRectorTest
 */
final class DocblockGetterReturnArrayFromPropertyDocblockVarRector extends AbstractRector
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

        if ($node->stmts === null) {
            return null;
        }

        // we need exactly one statement of return
        if (count($node->stmts) !== 1) {
            return null;
        }

        $onlyStmt = $node->stmts[0];
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

        $propertyFetchType = $this->getType($propertyFetch);

        $propertyFetchDocTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($propertyFetchType);

        $returnTagValueNode = new ReturnTagValueNode($propertyFetchDocTypeNode, '');
        $phpDocInfo->addTagValueNode($returnTagValueNode);

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

        return $node;
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
}
