<?php

declare(strict_types=1);

namespace Rector\Tests\Analyzer\SimpleScope;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\ParserFactory;
use PHPUnit\Framework\TestCase;
use Rector\Analyzer\SimpleScope\SimpleScope;
use Rector\Analyzer\SimpleScope\SimpleScopeResolver;
use Rector\Analyzer\SimpleType\MixedType;
use Rector\Analyzer\SimpleType\ObjectType;
use Rector\Analyzer\SimpleType\StringType;

final class SimpleScopeResolverTest extends TestCase
{
    private SimpleScopeResolver $simpleScopeResolver;

    protected function setUp(): void
    {
        $this->simpleScopeResolver = new SimpleScopeResolver();
    }

    public function testResolvesNewAssignToObjectType(): void
    {
        $simpleScope = $this->resolveCode(<<<'PHP'
<?php
function demo()
{
    $dateTime = new \DateTime();
}
PHP);

        $simpleType = $simpleScope->getType(new Variable('dateTime'));
        $this->assertInstanceOf(ObjectType::class, $simpleType);
        $this->assertTrue($simpleType->isInstanceOf('DateTime'));
    }

    public function testIsObjectType(): void
    {
        $simpleScope = $this->resolveCode(<<<'PHP'
<?php
function demo()
{
    $dateTime = new \DateTime();
}
PHP);

        $this->assertTrue($simpleScope->isObjectType(new Variable('dateTime'), 'DateTime'));
        $this->assertFalse($simpleScope->isObjectType(new Variable('dateTime'), 'stdClass'));
        $this->assertFalse($simpleScope->isObjectType(new Variable('missing'), 'DateTime'));
    }

    public function testResolvesTypedParam(): void
    {
        $simpleScope = $this->resolveCode(<<<'PHP'
<?php
function demo(string $name)
{
}
PHP);

        $this->assertInstanceOf(StringType::class, $simpleScope->getType(new Variable('name')));
    }

    public function testUnknownVariableIsMixed(): void
    {
        $simpleScope = $this->resolveCode(<<<'PHP'
<?php
function demo()
{
}
PHP);

        $this->assertInstanceOf(MixedType::class, $simpleScope->getType(new Variable('missing')));
    }

    public function testResolvesLiteralType(): void
    {
        $simpleScope = $this->resolveCode('<?php');

        $this->assertInstanceOf(StringType::class, $simpleScope->getType(new String_('hello')));
    }

    private function resolveCode(string $code): SimpleScope
    {
        $parser = new ParserFactory()
            ->createForNewestSupportedVersion();
        $stmts = $parser->parse($code);

        return $this->simpleScopeResolver->resolve($stmts ?? []);
    }
}
