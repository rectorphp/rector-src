<?php

declare(strict_types=1);

namespace Rector\DowngradePhp70\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\Rector\AbstractRector;
use Rector\DowngradePhp71\TypeDeclaration\PhpDocFromTypeDeclarationDecorator;
use Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use PHPStan\Type\ObjectType;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * @see \Rector\Tests\DowngradePhp70\Rector\ClassMethod\DowngradeParentTypeDeclarationRector\DowngradeParentTypeDeclarationRectorTest
 */
final class DowngradeParentTypeDeclarationRector extends AbstractRector
{
    public function __construct(
        private PhpDocFromTypeDeclarationDecorator $phpDocFromTypeDeclarationDecorator,
        private ReflectionProvider $reflectionProvider,
        private ReturnTypeInferer $returnTypeInferer,
        private PhpDocTypeChanger $phpDocTypeChanger
    ) {
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove "parent" return type, add a "@return TheParentClass" tag instead',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class ParentClass
{
}

class SomeClass extends ParentClass
{
    public function foo(): parent
    {
        return $this;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class ParentClass
{
}

class SomeClass extends ParentClass
{
    /**
     * @return parent
     */
    public function foo()
    {
        return $this;
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        $class = $node->getAttribute(AttributeKey::CLASS_NODE);
        if (! $class instanceof Class_) {
            return null;
        }

        if (! $class->extends instanceof FullyQualified) {
            return null;
        }

        if (! $node->returnType instanceof Name) {
            return null;
        }

        if (! $this->nodeNameResolver->isName($node->returnType, 'parent')) {
            return null;
        }

        $node->returnType = null;
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);

        if (! $phpDocInfo) {
            return null;
        }

        if ($phpDocInfo->hasByType(ReturnTagValueNode::class)) {
            return $node;
        }

        $this->phpDocTypeChanger->changeReturnType($phpDocInfo, new ObjectType($class->extends->toString()));
        return $node;
    }
}
