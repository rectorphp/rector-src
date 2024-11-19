<?php

declare(strict_types=1);

namespace Rector\PhpAttribute\NodeFactory;

use Nette\Utils\Strings;
use PhpParser\BuilderHelpers;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class NamedArgsFactory
{
    /**
     * @see https://regex101.com/r/1bJR0J/1
     * @var string
     */
    private const CLASS_CONST_REGEX = '#(?<class>\w+)::(?<constant>\w+)#';

    /**
     * @param array<string|int, mixed|Expr> $values
     * @return Arg[]
     */
    public function createFromValues(array $values): array
    {
        $args = [];

        foreach ($values as $key => $argValue) {
            $name = null;

            if ($argValue instanceof ArrayItem) {
                if ($argValue->key instanceof String_) {
                    $name = new Identifier($argValue->key->value);
                }

                $argValue = $argValue->value;
            }

            $expr = BuilderHelpers::normalizeValue($argValue);

            // for named arguments
            if (!$name instanceof Identifier && is_string($key)) {
                $name = new Identifier($key);
            }

            $this->normalizeStringDoubleQuote($expr);

            $args[] = new Arg($expr, false, false, [], $name);
        }

        return $args;
    }

    private function normalizeStringDoubleQuote(Expr $expr): void
    {
        if (! $expr instanceof String_) {
            return;
        }

        // avoid escaping quotes + preserve newlines
        if (! str_contains($expr->value, "'")) {
            return;
        }

        if (str_contains($expr->value, "\n")) {
            return;
        }

        $expr->setAttribute(AttributeKey::KIND, String_::KIND_DOUBLE_QUOTED);
    }
}
