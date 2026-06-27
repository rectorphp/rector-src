<?php

namespace Rector\Tests\CodeQuality\Rector\Attribute\ExplicitAttributeNamedArgsRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Attribute\ExplicitAttributeNamedArgsRector\Source\Middleware;
use Rector\Tests\CodeQuality\Rector\Attribute\ExplicitAttributeNamedArgsRector\Source\RequireToken;

#[Middleware(RequireToken::class, ['export'])]
class PositionalOnly
{
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Attribute\ExplicitAttributeNamedArgsRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Attribute\ExplicitAttributeNamedArgsRector\Source\Middleware;
use Rector\Tests\CodeQuality\Rector\Attribute\ExplicitAttributeNamedArgsRector\Source\RequireToken;

#[Middleware(middleware: RequireToken::class, only: ['export'])]
class PositionalOnly
{
}

?>
