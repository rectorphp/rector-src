<?php

namespace Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Source\MiddlewareWithVariadic;
use Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Source\RequireToken;

#[MiddlewareWithVariadic(RequireToken::class)]
class VariadicNamedPrefix
{
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Source\MiddlewareWithVariadic;
use Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Source\RequireToken;

#[MiddlewareWithVariadic(middleware: RequireToken::class)]
class VariadicNamedPrefix
{
}

?>
