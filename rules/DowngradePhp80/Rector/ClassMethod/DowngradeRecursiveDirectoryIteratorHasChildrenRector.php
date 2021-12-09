<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\Rector\AbstractRector;
use Rector\FamilyTree\Reflection\FamilyRelationsAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DowngradePhp80\Rector\ClassMethod\DowngradeRecursiveDirectoryIteratorHasChildrenRector\DowngradeRecursiveDirectoryIteratorHasChildrenRectorTest
 */
final class DowngradeRecursiveDirectoryIteratorHasChildrenRector extends AbstractRector
{
    public function __construct(private readonly FamilyRelationsAnalyzer $familyRelationsAnalyzer)
    {
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
            'Remove bool type hint on child of RecursiveDirectoryIterator hasChildren allowLinks parameter',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class RecursiveDirectoryIteratorChild extends \RecursiveDirectoryIterator
{
    public function hasChildren(bool $allowLinks = false): bool
    {
        return true;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class RecursiveDirectoryIteratorChild extends \RecursiveDirectoryIterator
{
    public function hasChildren($allowLinks = false): bool
    {
        return true;
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
        if (! $this->nodeNameResolver->isName($node, 'hasChildren')) {
            return null;
        }

        if (! isset($node->params[0])) {
            return null;
        }

        $classLike = $this->betterNodeFinder->findParentType($node, ClassLike::class);
        if (! $classLike instanceof ClassLike) {
            return null;
        }

        $ancestorClassNames = $this->familyRelationsAnalyzer->getClassLikeAncestorNames($classLike);
        if (! in_array('RecursiveDirectoryIterator', $ancestorClassNames, true)) {
            return null;
        }

        if ($node->params[0]->type === null) {
            return null;
        }

        $node->params[0]->type = null;
        return $node;
    }
}
