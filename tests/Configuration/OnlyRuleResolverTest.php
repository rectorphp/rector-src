<?php

declare(strict_types=1);

namespace Rector\Tests\Configuration;

use PHPStan\Reflection\ReflectionProvider;
use Rector\Configuration\OnlyRuleResolver;
use Rector\Contract\Rector\RectorInterface;
use Rector\DeadCode\Rector\Assign\RemoveDoubleAssignRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;
use Rector\Exception\Configuration\RectorRuleNameAmbiguousException;
use Rector\Exception\Configuration\RectorRuleNotFoundException;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector;

final class OnlyRuleResolverTest extends AbstractLazyTestCase
{
    private OnlyRuleResolver $onlyRuleResolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bootFromConfigFiles([__DIR__ . '/config/only_rule_resolver_config.php']);
        $rectorConfig = self::getContainer();

        $this->onlyRuleResolver = new OnlyRuleResolver(
            iterator_to_array($rectorConfig->tagged(RectorInterface::class)),
            $this->make(ReflectionProvider::class)
        );
    }

    public function testResolveOk(): void
    {
        $this->assertSame(
            RemoveDoubleAssignRector::class,
            $this->onlyRuleResolver->resolve('Rector\\DeadCode\\Rector\\Assign\\RemoveDoubleAssignRector')
        );
    }

    public function testResolveOkLeadingBackslash(): void
    {
        $this->assertSame(
            RemoveDoubleAssignRector::class,
            $this->onlyRuleResolver->resolve('\\Rector\\DeadCode\\Rector\\Assign\\RemoveDoubleAssignRector')
        );
    }

    public function testResolveOkDoubleBackslashes(): void
    {
        $this->assertSame(
            RemoveDoubleAssignRector::class,
            $this->onlyRuleResolver->resolve('\\\\Rector\\\\DeadCode\\\\Rector\\\\Assign\\\\RemoveDoubleAssignRector'),
            'We want to fix wrongly double-quoted backslashes automatically'
        );
    }

    public function testResolveOkSingleQuotes(): void
    {
        $this->assertSame(
            RemoveDoubleAssignRector::class,
            $this->onlyRuleResolver->resolve("'Rector\\DeadCode\\Rector\\Assign\\RemoveDoubleAssignRector'"),
            'Remove stray single quotes on Windows systems'
        );
    }

    public function testResolveMissingBackslash(): void
    {
        $this->assertSame(
            RemoveDoubleAssignRector::class,
            $this->onlyRuleResolver->resolve('RectorDeadCodeRectorAssignRemoveDoubleAssignRector'),
            'The shell eats unescaped backslashes, resolve the flattened rule name anyway'
        );
    }

    public function testResolveMissingBackslashNotFound(): void
    {
        $this->expectExceptionMessageIsOrContains(
            'Rule "ThisRuleDoesNotExist" was not found.' . PHP_EOL
                . 'The rule has no namespace. Make sure to escape the backslashes, and add quotes around the rule name: --only="My\\Rector\\Rule"'
        );
        $this->expectException(RectorRuleNotFoundException::class);

        $this->onlyRuleResolver->resolve('ThisRuleDoesNotExist');
    }

    public function testResolveNotFound(): void
    {
        $this->expectExceptionMessageIsOrContains(
            'Rule "This\Rule\Does\Not\Exist" was not found.' . PHP_EOL
                . 'Make sure it is registered in your config or in one of the sets'
        );
        $this->expectException(RectorRuleNotFoundException::class);

        $this->onlyRuleResolver->resolve('This\\Rule\\Does\\Not\\Exist');
    }

    public function testResolveShortOk(): void
    {
        $this->assertSame(
            RemoveUnusedPrivateMethodRector::class,
            $this->onlyRuleResolver->resolve('RemoveUnusedPrivateMethodRector'),
        );
    }

    public function testResolveShortOkTwoLevels(): void
    {
        $this->assertSame(
            RemoveDoubleAssignRector::class,
            $this->onlyRuleResolver->resolve('Assign\\RemoveDoubleAssignRector'),
        );
    }

    public function testResolveExistingButNotRegistered(): void
    {
        $this->expectExceptionMessageIsOrContains(
            'Rule "Rector\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector" exists, but is not registered in your Rector config.'
                . PHP_EOL . 'Register it in your rector.php:' . PHP_EOL . PHP_EOL
                . '    ->withRules([SafeDeclareStrictTypesRector::class])'
        );
        $this->expectException(RectorRuleNotFoundException::class);

        $this->onlyRuleResolver->resolve(SafeDeclareStrictTypesRector::class);
    }

    public function testResolveShortExistingButNotRegistered(): void
    {
        $this->expectExceptionMessageIsOrContains(
            'Rule "Rector\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector" exists, but is not registered in your Rector config.'
        );
        $this->expectException(RectorRuleNotFoundException::class);

        $this->onlyRuleResolver->resolve('SafeDeclareStrictTypesRector');
    }

    public function testResolveShortAmbiguous(): void
    {
        $this->expectExceptionMessageIsOrContains(
            'Short rule name "RemoveDoubleAssignRector" is ambiguous. Specify the full rule name:' . PHP_EOL
                . '- Rector\\DeadCode\\Rector\\Assign\\RemoveDoubleAssignRector' . PHP_EOL
                . '- Rector\\Tests\\Configuration\\Source\\RemoveDoubleAssignRector'
        );
        $this->expectException(RectorRuleNameAmbiguousException::class);

        $this->onlyRuleResolver->resolve('RemoveDoubleAssignRector');
    }
}
