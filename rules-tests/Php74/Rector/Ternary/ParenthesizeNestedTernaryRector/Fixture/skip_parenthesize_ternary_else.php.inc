<?php

namespace Rector\Tests\Php74\Rector\Ternary\ParenthesizeNestedTernaryRector\Fixture;

function skipParenthesizeTernaryElse(string $message): string
{
    $jsonParser = new JsonParser();
    return ! $jsonParser->lint($message) instanceof ParsingException
        ? 'application/problem+json'
        : (strip_tags($message) === $message ? 'text/plain' : 'text/html');
}
