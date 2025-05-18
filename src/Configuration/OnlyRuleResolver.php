<?php

declare(strict_types=1);

namespace Rector\Configuration;

use Rector\Contract\Rector\RectorInterface;
use Rector\Exception\Configuration\RectorRuleNameAmbigiousException;
use Rector\Exception\Configuration\RectorRuleNotFoundException;

/**
 * @see \Rector\Tests\Configuration\OnlyRuleResolverTest
 */
final readonly class OnlyRuleResolver
{
    /**
     * @param RectorInterface[] $rectors
     */
    public function __construct(
        private array $rectors
    ) {
    }

    public function resolve(string $rule): string
    {
        //fix wrongly double escaped backslashes
        $rule = str_replace('\\\\', '\\', $rule);

        //remove single quotes appearing when single-quoting arguments on windows
        if (str_starts_with($rule, "'") && str_ends_with($rule, "'")) {
            $rule = substr($rule, 1, -1);
        }

        $rule = ltrim($rule, '\\');

        foreach ($this->rectors as $rector) {
            if ($rector::class === $rule) {
                return $rule;
            }
        }

        //allow short rule names if there are not duplicates
        $matching = [];
        foreach ($this->rectors as $rector) {
            if (str_ends_with($rector::class, '\\' . $rule)) {
                $matching[] = $rector::class;
            }
        }

        $matching = array_unique($matching);
        if (count($matching) == 1) {
            return $matching[0];
        }

        if (count($matching) > 1) {
            sort($matching);
            $message = sprintf(
                'Short rule name "%s" is ambiguous. Specify the full rule name:' . PHP_EOL
                    . '- ' . implode(PHP_EOL . '- ', $matching),
                $rule
            );
            throw new RectorRuleNameAmbiguousException($message);
        }

        if (! str_contains($rule, '\\')) {
            $message = sprintf(
                'Rule "%s" was not found.%sThe rule has no namespace. Make sure to escape the backslashes, and add quotes around the rule name: --only="My\\Rector\\Rule"',
                $rule,
                PHP_EOL
            );
        } else {
            $message = sprintf(
                'Rule "%s" was not found.%sMake sure it is registered in your config or in one of the sets',
                $rule,
                PHP_EOL
            );
        }

        throw new RectorRuleNotFoundException($message);
    }
}
