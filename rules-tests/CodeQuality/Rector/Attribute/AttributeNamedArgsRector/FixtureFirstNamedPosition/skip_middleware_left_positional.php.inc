<?php

namespace Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\FixtureFirstNamedPosition;

use Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Source\Middleware;
use Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Source\RequireToken;

#[Middleware(RequireToken::class, except: ['home'])]
class SkipMiddlewareLeftPositional
{
}
