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
     * @see https://regex101.com/r/19qPHr/1
     */
    private const PLUS_MINUS_COUNT_REGEX = '#(?<operator>\+|-)(\\s+)?(?<count>\\d+)(\s+)?(?<unit>seconds|second|sec|minutes|minute|min|hours|hour|days|day|weeks|week|months|month|years|year)#';

    private const SET_TIME_REGEX = '#(?<hour>.\\d{1,2}):(?<minute>\\d{2})(:(?<second>\\d{2}))?#';

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

        $carbonCall = $this->createSetTimeMethodCall($carbonCall, $string);
        $string->value = Strings::replace($string->value, self::SET_TIME_REGEX);

        // Handle add/sub multiple times
        while ($match = Strings::match($string->value, self::PLUS_MINUS_COUNT_REGEX)) {
            $carbonCall = $this->createModifyMethodCall($carbonCall, new LNumber((int) $match['count']), $match['unit'], $match['operator']);
            $string->value = Strings::replace($string->value, self::PLUS_MINUS_COUNT_REGEX, '', 1);
        }

        // If we still have something in the string, we go back to the first method and replace this with a parse
        if (($rest = Strings::trim($string->value)) !== '') {
            $originialStaticCall = &$carbonCall;
            while (!$originialStaticCall instanceof StaticCall) {
                $originialStaticCall = &$originialStaticCall->var;
            }

            // If we fallback to a parse we want to include tomorrow/today/yesterday etc
            if ($originialStaticCall->name != 'now') {
                $rest .= ' ' . $originialStaticCall->name;
            }

            $originialStaticCall->name = new Identifier('parse');
            $originialStaticCall->args = [new Arg(new String_($rest))];
        }

        return $carbonCall;
    }

    private function createStaticCall(FullyQualified $carbonFullyQualified, String_ $string): StaticCall
    {
        $startDate = Strings::match($string->value, self::STATIC_DATE_REGEX)[0] ?? 'now';
        $carbonCall = new StaticCall($carbonFullyQualified, new Identifier($startDate));

        return $carbonCall;
    }

    private function createSetTimeMethodCall(StaticCall $carbonCall, String_ $string): MethodCall|StaticCall
    {
        $hour = null;
        $minute = null;
        $second = null;

        $matches = Strings::match($string->value, self::SET_TIME_REGEX);

        foreach($matches ?? [] as $group => $value) {
            switch ($group) {
                case 'hour':
                    $hour = (int) $value;
                    break;
                case 'minute':
                    $minute = (int) $value;
                    break;
                case 'second':
                    $second = (int) $value;
                    break;
                default:
                    break;
            }
        }

        if (!isset($hour) || !isset($minute)) {
            return $carbonCall;
        }

        // Use today when we set a time so base is always 00:00:00
        $carbonCall->name = new Identifier('today');
        $second = $second ?? 0;
        if (($hour > 0) || ($minute > 0) || ($second > 0)) {
            $args = [new Arg(new LNumber($hour)), new Arg(new LNumber($minute))];
            if ($second > 0) {
                $args[] = new Arg(new LNumber($second));
            }

            return new MethodCall($carbonCall, new Identifier('setTime'), $args);
        }

        return $carbonCall;
    }

    private function createModifyMethodCall(MethodCall|StaticCall $carbonCall, LNumber $countLNumber, string $unit, string $operator): MethodCall|null
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
