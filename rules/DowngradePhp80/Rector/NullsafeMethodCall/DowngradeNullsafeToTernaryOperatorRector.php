<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\Rector\NullsafeMethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\NullsafePropertyFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Variable;
use Rector\Core\Rector\AbstractRector;
use Rector\Naming\Naming\VariableNaming;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DowngradePhp80\Rector\NullsafeMethodCall\DowngradeNullsafeToTernaryOperatorRector\DowngradeNullsafeToTernaryOperatorRectorTest
 */
final class DowngradeNullsafeToTernaryOperatorRector extends AbstractRector
{
    public function __construct(
        private readonly VariableNaming $variableNaming
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change nullsafe operator to ternary operator rector', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$dateAsString = $booking->getStartDate()?->asDateTimeString();
$dateAsString = $booking->startDate?->dateTimeString;
CODE_SAMPLE
,
                <<<'CODE_SAMPLE'
$dateAsString = ($bookingGetStartDate = $booking->getStartDate()) ? $bookingGetStartDate->asDateTimeString() : null;
$dateAsString = ($bookingGetStartDate = $booking->startDate) ? $bookingGetStartDate->dateTimeString : null;
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [NullsafeMethodCall::class, NullsafePropertyFetch::class];
    }

    /**
     * @param NullsafeMethodCall|NullsafePropertyFetch $node
     */
    public function refactor(Node $node): Ternary
    {
        $scope = $node->getAttribute(AttributeKey::SCOPE);

        $tempVarName = $this->variableNaming->resolveFromNodeWithScopeCountAndFallbackName(
            $node->var,
            $scope,
            '_'
        );

        $variable = new Variable($tempVarName);
        $called = $node instanceof NullsafeMethodCall
            ? new MethodCall($variable, $node->name, $node->args)
            : new PropertyFetch($variable, $node->name);

        $assign = new Assign($variable, $node->var);
        return new Ternary($assign, $called, $this->nodeFactory->createNull());
    }
}
