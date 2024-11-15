<?php

declare(strict_types=1);

namespace Rector\Configuration;

use Rector\Contract\Rector\RectorInterface;
use Rector\Exception\Configuration\RectorRuleNotFoundException;

/**
 * @see \Rector\Tests\Configuration\OnlyRuleResolverTest
 */
final class OnlyRuleResolver
{
    /**
     * @param RectorInterface[] $rectors
     */
    public function __construct(
        private readonly array $rectors
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

        if (strpos($rule, '\\') === false) {
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
