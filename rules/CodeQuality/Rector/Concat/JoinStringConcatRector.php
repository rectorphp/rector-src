<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Concat;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\Concat\JoinStringConcatRector\JoinStringConcatRectorTest
 */
final class JoinStringConcatRector extends AbstractRector
{
    /**
     * @var int
     */
    private const LINE_BREAK_POINT = 100;

    private bool $nodeReplacementIsRestricted = false;

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Joins concat of 2 strings, unless the length is too long',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $name = 'Hi' . ' Tom';
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $name = 'Hi Tom';
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Concat::class];
    }

    /**
     * @param Concat $node
     */
    public function refactor(Node $node): ?Node
    {
        $this->nodeReplacementIsRestricted = false;

        if (! $this->isTopMostConcatNode($node)) {
            return null;
        }

        $joinedNode = $this->joinConcatIfStrings($node);
        if (! $joinedNode instanceof String_) {
            return null;
        }

        if ($this->nodeReplacementIsRestricted) {
            return null;
        }

        return $joinedNode;
    }

    private function isTopMostConcatNode(Concat $concat): bool
    {
        $parent = $concat->getAttribute(AttributeKey::PARENT_NODE);
        return ! $parent instanceof Concat;
    }

    /**
     * @return Concat|String_
     */
    private function joinConcatIfStrings(Concat $node): Concat | String_
    {
        $concat = clone $node;

        if ($concat->left instanceof Concat) {
            $concat->left = $this->joinConcatIfStrings($concat->left);
        }

        if ($concat->right instanceof Concat) {
            $concat->right = $this->joinConcatIfStrings($concat->right);
        }

        if (! $concat->left instanceof String_) {
            return $node;
        }

        if (! $concat->right instanceof String_) {
            return $node;
        }

        $leftValue = $concat->left->value;
        $rightValue = $concat->right->value;

        if ($leftValue === "\n") {
            $this->nodeReplacementIsRestricted = true;
            return $node;
        }

        if ($rightValue === "\n") {
            $this->nodeReplacementIsRestricted = true;
            return $node;
        }

        $resultString = new String_($leftValue . $rightValue);
        if (Strings::length($resultString->value) >= self::LINE_BREAK_POINT) {
            $this->nodeReplacementIsRestricted = true;
            return $node;
        }

        return $resultString;
    }
}
