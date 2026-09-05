<?php

declare(strict_types=1);

namespace Rector\Tests\SimpleScope;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\ParserFactory;
use PHPUnit\Framework\TestCase;
use Rector\SimpleScope\SimpleScope;
use Rector\SimpleScope\SimpleScopeResolver;

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

        $this->assertSame('DateTime', $simpleScope->getType(new Variable('dateTime'))->describe());
    }

    public function testResolvesTypedParam(): void
    {
        $simpleScope = $this->resolveCode(<<<'PHP'
<?php
function demo(string $name)
{
}
PHP);

        $this->assertSame('string', $simpleScope->getType(new Variable('name'))->describe());
    }

    public function testUnknownVariableIsMixed(): void
    {
        $simpleScope = $this->resolveCode(<<<'PHP'
<?php
function demo()
{
}
PHP);

        $this->assertSame('mixed', $simpleScope->getType(new Variable('missing'))->describe());
    }

    public function testResolvesLiteralType(): void
    {
        $simpleScope = $this->resolveCode('<?php');

        $this->assertSame('string', $simpleScope->getType(new String_('hello'))->describe());
    }

    private function resolveCode(string $code): SimpleScope
    {
        $parser = new ParserFactory()->createForNewestSupportedVersion();
        $stmts = $parser->parse($code);

        return $this->simpleScopeResolver->resolve($stmts ?? []);
    }
}
