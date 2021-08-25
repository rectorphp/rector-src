<?php

declare(strict_types=1);

namespace Rector\DowngradePhp70\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\Type\ThisType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DowngradePhp70\Rector\ClassMethod\DowngradeParentTypeDeclarationRector\DowngradeParentTypeDeclarationRectorTest
 */
final class DowngradeParentTypeDeclarationRector extends AbstractRector
{
    public function __construct(
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
            'Remove "parent" return type, add a "@return parent" tag instead',
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
        if (! $node->returnType instanceof Name) {
            return null;
        }

        if (! $this->nodeNameResolver->isName($node->returnType, 'parent')) {
            return null;
        }

        $node->returnType = null;

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        if ($phpDocInfo->hasByType(ReturnTagValueNode::class)) {
            return $node;
        }

        $class = $node->getAttribute(AttributeKey::CLASS_NODE);
        $type = $this->getType($class, $node);

        $this->phpDocTypeChanger->changeReturnType($phpDocInfo, $type);
        return $node;
    }

    private function getType(?ClassLike $classLike, ClassMethod $classMethod): ThisType|Type
    {
        if (! $classLike instanceof ClassLike) {
            return new ThisType('');
        }

        if (! $classLike instanceof Class_) {
            return new ThisType('');
        }

        if ($classLike->extends instanceof FullyQualified) {
            return $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType(
                new IdentifierTypeNode('parent'),
                $classMethod
            );
        }

        // handle when parent removed by other rules
        return new ThisType('');
    }
}
