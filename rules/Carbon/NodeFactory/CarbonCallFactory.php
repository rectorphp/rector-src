<?php

declare (strict_types=1);
namespace Rector\Carbon\NodeFactory;

use RectorPrefix202407\Nette\Utils\Strings;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
final class CarbonCallFactory
{
    /**
     * @var string
     * @see https://regex101.com/r/19qPHr/1
     */
    private const PLUS_MINUS_COUNT_REGEX = '#(?<operator>\+|-)(\\s+)?(?<count>\\d+)(\s+)?(?<unit>sec|second|seconds|min|minute|minutes|hour|hours|day|days|week|weeks|month|months)#';

    /**
     * @return \PhpParser\Node\Expr\MethodCall|\PhpParser\Node\Expr\StaticCall
     */
    public function createFromDateTimeString(FullyQualified $carbonFullyQualified, String_ $string)
    {
        $dateTimeValue = $string->value;
        if (in_array($dateTimeValue, ['now', 'today', 'yesterday', 'tomorrow'])) {
            return new StaticCall($carbonFullyQualified, new Identifier($dateTimeValue));
        }

        $startDate = Strings::match($dateTimeValue, '#now|yesterday|today|tomorrow#')[0] ?? 'now';
        $carbonCall = new StaticCall($carbonFullyQualified, new Identifier($startDate));

        $match = Strings::match($dateTimeValue, self::PLUS_MINUS_COUNT_REGEX);

        if ($match === null) {
            return new StaticCall($carbonFullyQualified, new Identifier('parse'), [new Arg($string)]);
        }

        $countLNumber = new LNumber((int) $match['count']);

        $unit = match((string) $match['unit']) {
            'sec', 'second', 'seconds' => 'seconds',
            'min', 'minute', 'minutes' => 'minutes',
            'hour', 'hours' => 'hours',
            'day', 'days' => 'days',
            'week', 'weeks' => 'weeks',
            'month', 'months' => 'months',
        };

        $methodName = match((string) $match['operator']) {
            '+' => 'add',
            '-' => 'sub',
        } . ucfirst($unit);

        return new MethodCall($carbonCall, new Identifier($methodName), [new Arg($countLNumber)]);
    }
}
