<?php

declare(strict_types=1);

namespace Rector\Php84\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\NewMethodCallWithoutParenthesesRectorTest
 */
final class NewMethodCallWithoutParenthesesRector extends AbstractRector implements MinPhpVersionInterface
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove parentheses on new method call with parentheses',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
(new Request())->withMethod('GET')->withUri('/hello-world');
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
new Request()->withMethod('GET')->withUri('/hello-world');
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->var instanceof New_) {
            return null;
        }

        // process here..
        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::DEPRECATE_IMPLICIT_NULLABLE_PARAM_TYPE;
    }
}