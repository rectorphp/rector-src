<?php

declare(strict_types=1);

namespace Rector\Php70\Rector\Variable;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php70\Rector\Variable\WrapVariableVariableNameInCurlyBracesRector\WrapVariableVariableNameInCurlyBracesRectorTest
 */
final class WrapVariableVariableNameInCurlyBracesRector extends AbstractRector implements MinPhpVersionInterface
{
    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::WRAP_VARIABLE_VARIABLE;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Ensure variable variables are wrapped in curly braces',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
function run($foo)
{
    global $$foo->bar;
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
function run($foo)
{
    global ${$foo->bar};
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
        return [ArrayDimFetch::class, Variable::class];
    }

    /**
     * @param ArrayDimFetch|Variable $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof ArrayDimFetch) {
            if (! $node->var instanceof Variable) {
                return null;
            }

            if (is_string($node->var->name)) {
                return null;
            }

            return new Variable(new ArrayDimFetch(
                $node->var->name,
                $node->dim
            ));
        }

        $nodeName = $node->name;

        if (! $nodeName instanceof PropertyFetch && ! $nodeName instanceof Variable) {
            return null;
        }

        if ($node->getEndTokenPos() !== $nodeName->getEndTokenPos()) {
            return null;
        }

        if ($nodeName instanceof PropertyFetch) {
            return new Variable(new PropertyFetch($nodeName->var, $nodeName->name));
        }

        return new Variable(new Variable($nodeName->name));
    }
}
