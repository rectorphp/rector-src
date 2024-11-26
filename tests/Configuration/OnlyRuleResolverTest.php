<?php

declare(strict_types=1);

namespace Rector\Tests\Configuration;

use Rector\Configuration\OnlyRuleResolver;
use Rector\Contract\Rector\RectorInterface;
use Rector\Exception\Configuration\RectorRuleNameAmbigiousException;
use Rector\Exception\Configuration\RectorRuleNotFoundException;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class OnlyRuleResolverTest extends AbstractLazyTestCase
{
    protected OnlyRuleResolver $resolver;

    protected function setUp(): void
    {
        $this->bootFromConfigFiles([__DIR__ . '/config/only_rule_resolver_config.php']);
        $rectorConfig = self::getContainer();

        $this->resolver = new OnlyRuleResolver(iterator_to_array($rectorConfig->tagged(RectorInterface::class)));
    }

    public function testResolveOk(): void
    {
        $this->assertEquals(
            \Rector\DeadCode\Rector\Assign\RemoveDoubleAssignRector::class,
            $this->resolver->resolve('Rector\\DeadCode\\Rector\\Assign\\RemoveDoubleAssignRector')
        );
    }

    public function testResolveOkLeadingBackslash(): void
    {
        $this->assertEquals(
            \Rector\DeadCode\Rector\Assign\RemoveDoubleAssignRector::class,
            $this->resolver->resolve('\\Rector\\DeadCode\\Rector\\Assign\\RemoveDoubleAssignRector')
        );
    }

    public function testResolveOkDoubleBackslashes(): void
    {
        $this->assertEquals(
            \Rector\DeadCode\Rector\Assign\RemoveDoubleAssignRector::class,
            $this->resolver->resolve('\\\\Rector\\\\DeadCode\\\\Rector\\\\Assign\\\\RemoveDoubleAssignRector'),
            'We want to fix wrongly double-quoted backslashes automatically'
        );
    }

    public function testResolveOkSingleQuotes(): void
    {
        $this->assertEquals(
            \Rector\DeadCode\Rector\Assign\RemoveDoubleAssignRector::class,
            $this->resolver->resolve("'Rector\\DeadCode\\Rector\\Assign\\RemoveDoubleAssignRector'"),
            'Remove stray single quotes on Windows systems'
        );
    }

    public function testResolveMissingBackslash(): void
    {
        $this->expectExceptionMessage(
            'Rule "RectorDeadCodeRectorAssignRemoveDoubleAssignRector" was not found.' . PHP_EOL
                . 'The rule has no namespace. Make sure to escape the backslashes, and add quotes around the rule name: --only="My\\Rector\\Rule"'
        );
        $this->expectException(RectorRuleNotFoundException::class);

        $this->resolver->resolve('RectorDeadCodeRectorAssignRemoveDoubleAssignRector');
    }

    public function testResolveNotFound(): void
    {
        $this->expectExceptionMessage(
            'Rule "This\Rule\Does\Not\Exist" was not found.' . PHP_EOL
                . 'Make sure it is registered in your config or in one of the sets'
        );
        $this->expectException(RectorRuleNotFoundException::class);

        $this->resolver->resolve('This\\Rule\\Does\\Not\\Exist');
    }

    public function testResolveShortOk(): void
    {
        $this->assertEquals(
            \Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector::class,
            $this->resolver->resolve('RemoveUnusedPrivateMethodRector'),
        );
    }

    public function testResolveShortOkTwoLevels(): void
    {
        $this->assertEquals(
            \Rector\DeadCode\Rector\Assign\RemoveDoubleAssignRector::class,
            $this->resolver->resolve('Assign\\RemoveDoubleAssignRector'),
        );
    }

    public function testResolveShortAmbiguous(): void
    {
        $this->expectExceptionMessage(
            'Short rule name "RemoveDoubleAssignRector" is ambiguous. Specify the full rule name:' . PHP_EOL
                . '- Rector\\DeadCode\\Rector\\Assign\\RemoveDoubleAssignRector' . PHP_EOL
                . '- Rector\\Tests\\Configuration\\Source\\RemoveDoubleAssignRector'
        );
        $this->expectException(RectorRuleNameAmbigiousException::class);

        $this->resolver->resolve('RemoveDoubleAssignRector');
    }
}
