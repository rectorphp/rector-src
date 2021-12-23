<?php

declare(strict_types=1);

namespace Rector\DowngradePhp70\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Clone_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use Rector\Core\Rector\AbstractRector;
use Rector\Naming\Naming\VariableNaming;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://www.php.net/manual/en/migration70.new-features.php#migration70.new-features.others
 *
 * @see \Rector\Tests\DowngradePhp70\Rector\MethodCall\DowngradeMethodCallOnCloneRector\DowngradeMethodCallOnCloneRectorTest
 */
final class DowngradeMethodCallOnCloneRector extends AbstractRector
{
    public function __construct(
        private readonly VariableNaming $variableNaming
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace (clone $obj)->call() to object assign and call',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
(clone $this)->execute();
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$object = (clone $this);
$object->execute();
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
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?MethodCall
    {
        if (! $node->var instanceof Clone_) {
            return null;
        }

        $scope = $node->getAttribute(AttributeKey::SCOPE);
        $newVariableName = $this->variableNaming->createCountedValueName('object', $scope);
        $variable = new Variable($newVariableName);
        $currentStatement = $node->getAttribute(AttributeKey::CURRENT_STATEMENT);

        $this->nodesToAddCollector->addNodeBeforeNode(
            new Expression(new Assign($variable, $node->var)),
            $currentStatement
        );
        $node->var = $variable;

        return $node;
    }
}
