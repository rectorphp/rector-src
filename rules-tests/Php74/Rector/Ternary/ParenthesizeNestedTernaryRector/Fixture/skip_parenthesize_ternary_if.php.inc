<?php

namespace Rector\Tests\Php74\Rector\Ternary\ParenthesizeNestedTernaryRector\Fixture;

function skipParenthesizeTernaryIf(string $message): string
{
    $jsonParser = new JsonParser();
    return $jsonParser->lint($message) instanceof ParsingException
        ? (strip_tags($message) === $message ? 'text/plain' : 'text/html')
        : 'application/problem+json';
}
