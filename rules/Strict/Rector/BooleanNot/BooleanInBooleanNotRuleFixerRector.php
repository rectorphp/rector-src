<?php

declare(strict_types=1);

namespace Rector\Strict\Rector\BooleanNot;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BooleanNot;
use PHPStan\Analyser\Scope;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Strict\NodeFactory\ExactCompareFactory;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * Fixer Rector for PHPStan rule:
 * https://github.com/phpstan/phpstan-strict-rules/blob/0705fefc7c9168529fd130e341428f5f10f4f01d/src/Rules/BooleansInConditions/BooleanInBooleanNotRule.php
 *
 * @see \Rector\Tests\Strict\Rector\BooleanNot\BooleanInBooleanNotRuleFixerRector\BooleanInBooleanNotRuleFixerRectorTest
 */
final class BooleanInBooleanNotRuleFixerRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const TREAT_AS_NON_EMPTY = 'treat_as_non_empty';

    private bool $treatAsNonEmpty = false;

    public function __construct(
        private ExactCompareFactory $exactCompareFactory
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        $errorMessage = \sprintf(
            'Fixer for PHPStan reports by strict type rule - "%s"',
            'PHPStan\Rules\BooleansInConditions\BooleanInBooleanNotRule'
        );
        return new RuleDefinition($errorMessage, [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run(string $name)
    {
        if (! $name) {
            return 'no name';
        }

        return 'name';
    }
}
CODE_SAMPLE
            ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run(string $name)
    {
        if ($name === '') {
            return 'no name';
        }

        return 'name';
    }
}
CODE_SAMPLE
                ,
                [
                    self::TREAT_AS_NON_EMPTY => false,
                ]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [BooleanNot::class];
    }

    /**
     * @param BooleanNot $node
     */
    public function refactor(Node $node): ?Expr
    {
        $scope = $node->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return null;
        }

        $exprType = $scope->getType($node->expr);

        return $this->exactCompareFactory->createIdenticalFalsyCompare($exprType, $node->expr, $this->treatAsNonEmpty);
    }

    /**
     * @param array<string, mixed> $configuration
     */
    public function configure(array $configuration): void
    {
        $treatAsNonEmpty = $configuration[self::TREAT_AS_NON_EMPTY] ?? false;
        Assert::boolean($treatAsNonEmpty);

        $this->treatAsNonEmpty = false;
    }
}
