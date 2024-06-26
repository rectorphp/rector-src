<?php

declare(strict_types=1);

namespace Rector\Carbon\Rector\New_;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Carbon\NodeFactory\CarbonCallFactory;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\DateTimeInstanceToCarbonRectorTest
 */
final class DateTimeInstanceToCarbonRector extends AbstractRector
{
    public function __construct(
        private readonly CarbonCallFactory $carbonCallFactory
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Convert new DateTime() to Carbon::*()', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$date = new \DateTime('today');
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
$date = \Carbon\Carbon::today();
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [New_::class];
    }

    /**
     * @param New_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->isFirstClassCallable()) {
            return null;
        }

        if ($this->isName($node->class, 'DateTime')) {
            return $this->refactorWithClass($node, 'Carbon\\Carbon');
        }

        if ($this->isName($node->class, 'DateTimeImmutable')) {
            return $this->refactorWithClass($node, 'Carbon\\CarbonImmutable');
        }

        return null;
    }

    private function refactorWithClass(New_ $new, string $className): MethodCall|StaticCall|null
    {
        // no arg? ::now()
        $carbonFullyQualified = new FullyQualified($className);

        if ($new->args === []) {
            return new StaticCall($carbonFullyQualified, new Identifier('now'));
        }

        if (count($new->getArgs()) === 1) {
            $firstArg = $new->getArgs()[0];

            if ($firstArg->value instanceof String_) {
                return $this->carbonCallFactory->createFromDateTimeString($carbonFullyQualified, $firstArg->value);
            }
        }

        return null;
    }
}
