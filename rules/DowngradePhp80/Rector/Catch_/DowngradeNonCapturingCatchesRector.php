<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\Rector\Catch_;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Catch_;
use Rector\Core\Rector\AbstractRector;
use Rector\Naming\Naming\VariableNaming;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/non-capturing_catches
 *
 * @see \Rector\Tests\DowngradePhp80\Rector\Catch_\DowngradeNonCapturingCatchesRector\DowngradeNonCapturingCatchesRectorTest
 */
final class DowngradeNonCapturingCatchesRector extends AbstractRector
{
    public function __construct(private readonly VariableNaming $variableNaming)
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Downgrade catch () without variable to one', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        try {
            // code
        } catch (\Exception) {
            // error
        }
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        try {
            // code
        } catch (\Exception $exception) {
            // error
        }
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
        return [Catch_::class];
    }

    /**
     * @param Catch_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->var !== null) {
            return null;
        }

        $scope = $node->getAttribute(AttributeKey::SCOPE);
        $exceptionVarName = $this->variableNaming->createCountedValueName('exception', $scope);
        $node->var = new Variable($exceptionVarName);

        return $node;
    }
}
