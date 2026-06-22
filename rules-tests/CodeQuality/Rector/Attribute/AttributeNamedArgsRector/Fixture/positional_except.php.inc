<?php

namespace Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Source\Middleware;
use Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Source\RequireToken;

#[Middleware(RequireToken::class, null, ['home'])]
class PositionalExcept
{
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Source\Middleware;
use Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Source\RequireToken;

#[Middleware(middleware: RequireToken::class, only: null, except: ['home'])]
class PositionalExcept
{
}

?>
