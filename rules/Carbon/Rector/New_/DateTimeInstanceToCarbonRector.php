<?php

declare(strict_types=1);

namespace Rector\Carbon\Rector\New_;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://github.com/briannesbitt/Carbon/issues/231
 *
 * @see \Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\DateTimeInstanceToCarbonRectorTest
 */
final class DateTimeInstanceToCarbonRector extends AbstractRector
{
    /**
     * @var string
     * @see https://regex101.com/r/9vGt8r/1
     */
    private const DAY_COUNT_REGEX = '#\+(\s+)?(?<count>\d+)(\s+)?(day|days)#';

    /**
     * @var string
     * @see https://regex101.com/r/6VUUQF/1
     */
    private const MONTH_COUNT_REGEX = '#\+(\s+)?(?<count>\d+)(\s+)?(month|months)#';

    /**
     * @var array<self::*_REGEX, string>
     */
    private const REGEX_TO_METHOD_NAME_MAP = [
        self::DAY_COUNT_REGEX => 'addDays',
        self::MONTH_COUNT_REGEX => 'addMonths',
    ];

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
        if (! $this->isName($node->class, 'DateTime')) {
            return null;
        }

        // no arg? ::now()
        $carbonFullyQualified = new FullyQualified('Carbon\Carbon');
        if ($node->args === []) {
            return new StaticCall($carbonFullyQualified, new Identifier('now'));
        }

        if (count($node->getArgs()) === 1) {
            $firstArg = $node->getArgs()[0];

            if ($firstArg->value instanceof String_) {
                return $this->createFromDateTimeString($carbonFullyQualified, $firstArg->value);
            }
        }

        return null;
    }

    private function createFromDateTimeString(
        FullyQualified $carbonFullyQualified,
        String_ $string
    ): MethodCall|StaticCall {
        $dateTimeValue = $string->value;
        if ($dateTimeValue === 'now') {
            return new StaticCall($carbonFullyQualified, new Identifier('now'));
        }

        if ($dateTimeValue === 'today') {
            return new StaticCall($carbonFullyQualified, new Identifier('today'));
        }

        $hasToday = Strings::match($dateTimeValue, '#today#');
        if ($hasToday !== null) {
            $carbonCall = new StaticCall($carbonFullyQualified, new Identifier('today'));
        } else {
            $carbonCall = new StaticCall($carbonFullyQualified, new Identifier('now'));
        }

        foreach (self::REGEX_TO_METHOD_NAME_MAP as $regex => $methodName) {
            $match = Strings::match($dateTimeValue, $regex);
            if ($match === null) {
                continue;
            }

            $countLNumber = new LNumber((int) $match['count']);

            $carbonCall = new MethodCall($carbonCall, new Identifier($methodName), [new Arg($countLNumber)]);
        }

        return $carbonCall;
    }
}
