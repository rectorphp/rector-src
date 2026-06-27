<?php

namespace Rector\Tests\CodeQuality\Rector\Attribute\ExplicitAttributeNamedArgsRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Attribute\ExplicitAttributeNamedArgsRector\Source\MiddlewareWithVariadic;
use Rector\Tests\CodeQuality\Rector\Attribute\ExplicitAttributeNamedArgsRector\Source\RequireToken;

#[MiddlewareWithVariadic(RequireToken::class)]
class VariadicNamedPrefix
{
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Attribute\ExplicitAttributeNamedArgsRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Attribute\ExplicitAttributeNamedArgsRector\Source\MiddlewareWithVariadic;
use Rector\Tests\CodeQuality\Rector\Attribute\ExplicitAttributeNamedArgsRector\Source\RequireToken;

#[MiddlewareWithVariadic(middleware: RequireToken::class)]
class VariadicNamedPrefix
{
}

?>
