<?php

namespace Rector\Tests\CodeQuality\Rector\Attribute\ExplicitAttributeNamedArgsRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Attribute\ExplicitAttributeNamedArgsRector\Source\MiddlewareWithVariadic;
use Rector\Tests\CodeQuality\Rector\Attribute\ExplicitAttributeNamedArgsRector\Source\RequireToken;

#[MiddlewareWithVariadic(RequireToken::class, 'a', 'b')]
class SkipVariadic
{
}
