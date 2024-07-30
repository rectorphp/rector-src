<?php

declare (strict_types=1);
namespace Rector\Carbon\NodeFactory;

use Nette\Utils\Strings;
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
     * @see https://regex101.com/r/LLMrFw/1
     */
    private const PLUS_MINUS_COUNT_REGEX = '#(?<operator>\+|-)(\\s+)?(?<count>\\d+)(\s+)?(?<unit>seconds|second|sec|minutes|minute|min|hours|hour|days|day|weeks|week|months|month|years|year)#';

    /**
     * @var string
     * @see https://regex101.com/r/tMpHK4/1
     */
    private const SET_TIME_REGEX = '#(?<hour>\\d{1,2}):(?<minute>\\d{2})(:(?<second>\\d{2}))?#';

    /**
     * @var string
     * @see https://regex101.com/r/HrppaL/1
     */
    private const SET_DATE_REGEX = '#(?<year>\d{4})-(?<month>\d{1,2})-(?<day>\d{1,2})?#';

    /**
     * @var string
     * @see https://regex101.com/r/IhxHTO/1
     */
    private const STATIC_DATE_REGEX = '#now|yesterday|today|tomorrow#';

    /**
     * @return \PhpParser\Node\Expr\MethodCall|\PhpParser\Node\Expr\StaticCall
     */
    public function createFromDateTimeString(FullyQualified $carbonFullyQualified, String_ $string)
    {
        $carbonCall = $this->createStaticCall($carbonFullyQualified, $string);
        $string->value = Strings::replace($string->value, self::STATIC_DATE_REGEX);

        $carbonCall = $this->createSetDateMethodCall($carbonCall, $string);
        $string->value = Strings::replace($string->value, self::SET_DATE_REGEX);

        $carbonCall = $this->createSetTimeMethodCall($carbonCall, $string);
        $string->value = Strings::replace($string->value, self::SET_TIME_REGEX);

        // Handle add/sub multiple times
        while ($match = Strings::match($string->value, self::PLUS_MINUS_COUNT_REGEX)) {
            $methodCall = $this->createModifyMethodCall($carbonCall, new LNumber((int) $match['count']), $match['unit'], $match['operator']);
            if ($methodCall) {
                $carbonCall = $methodCall;
                $string->value = Strings::replace($string->value, self::PLUS_MINUS_COUNT_REGEX, '', 1);
            }
        }

        // If we still have something in the string, we go back to the first method and replace this with a parse
        if (($rest = Strings::trim($string->value)) !== '') {
            $currentCall = $carbonCall;
            $callStack = [];
            while ($currentCall instanceof MethodCall) {
                $callStack[] = $currentCall;
                $currentCall = $currentCall->var;
            }

            if (! $currentCall instanceof StaticCall) {
                return $carbonCall;
            }

            // If we fallback to a parse we want to include tomorrow/today/yesterday etc
            if ($currentCall->name instanceof Identifier) {
                 if ($currentCall->name->name != 'now') {
                     $rest .= ' ' . $currentCall->name->name;
                 }
            }

            $currentCall->name = new Identifier('parse');
            $currentCall->args = [new Arg(new String_($rest))];

            // Rebuild original call from callstack
            foreach(array_reverse($callStack) as $call) {
                $call->var = $currentCall;
                $currentCall = $call;
            }

            $carbonCall = $currentCall;
        }

        return $carbonCall;
    }

    private function createStaticCall(FullyQualified $carbonFullyQualified, String_ $string): StaticCall
    {
        $startDate = Strings::match($string->value, self::STATIC_DATE_REGEX)[0] ?? 'now';
        $carbonCall = new StaticCall($carbonFullyQualified, new Identifier($startDate));

        return $carbonCall;
    }

    private function createSetDateMethodCall(StaticCall|MethodCall $carbonCall, String_ $string): StaticCall|MethodCall
    {
        $match = Strings::match($string->value, self::SET_DATE_REGEX);

        $year = (int)($match['year'] ?? 0);
        $month = (int)($match['month'] ?? 0);
        $day = (int)($match['day'] ?? 0);

        if (($year > 0) && ($month > 0) && ($day > 0)) {
            return new MethodCall($carbonCall, new Identifier('setDate'), [
                new Arg(new LNumber($year)),
                new Arg(new LNumber($month)),
                new Arg(new LNumber($day))
            ]);
        }

        return $carbonCall;
    }

    private function createSetTimeMethodCall(StaticCall|MethodCall $carbonCall, String_ $string): StaticCall|MethodCall
    {
        $match = Strings::match($string->value, self::SET_TIME_REGEX);

        $hour = (int)($match['hour'] ?? 0);
        $minute = (int)($match['minute'] ?? 0);
        $second = (int)($match['second'] ?? 0);

        if (($hour > 0) || ($minute > 0) || ($second > 0)) {
            return new MethodCall($carbonCall, new Identifier('setTime'), [
                new Arg(new LNumber($hour)),
                new Arg(new LNumber($minute)),
                new Arg(new LNumber($second))
            ]);
        }

        return $carbonCall;
    }

    private function createModifyMethodCall(MethodCall|StaticCall $carbonCall, LNumber $countLNumber, string $unit, string $operator): ?MethodCall
    {
        $unit = match($unit) {
            'sec', 'second', 'seconds' => 'seconds',
            'min', 'minute', 'minutes' => 'minutes',
            'hour', 'hours' => 'hours',
            'day', 'days' => 'days',
            'week', 'weeks' => 'weeks',
            'month', 'months' => 'months',
            'year', 'years' => 'years',
            default => null,
        };

        $operator = match($operator) {
            '+' => 'add',
            '-' => 'sub',
            default => null,
        };

        if ($unit === null || $operator === null) {
            return null;
        }

        $methodName = $operator . ucfirst($unit);

        return new MethodCall($carbonCall, new Identifier($methodName), [new Arg($countLNumber)]);
    }
}
