<?php

namespace Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Source\Middleware;
use Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Source\RequireToken;

#[Middleware(RequireToken::class, ['export'])]
class PositionalOnly
{
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Source\Middleware;
use Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Source\RequireToken;

#[Middleware(middleware: RequireToken::class, only: ['export'])]
class PositionalOnly
{
}

?>
