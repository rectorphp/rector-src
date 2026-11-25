<?php

declare(strict_types=1);

namespace Rector\Tests\Configuration;

use Rector\Config\RectorConfig;
use Rector\Configuration\ConfigDumper;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class ConfigDumperTest extends AbstractLazyTestCase
{
    private RectorConfig $freshRectorConfig;

    private ConfigDumper $configDumper;

    protected function setUp(): void
    {
        parent::setUp();

        // the parameter provider is static, reset it between tests
        foreach ([
            Option::PATHS,
            Option::SKIP,
            Option::REGISTERED_RECTOR_RULES,
            Option::RULE_CONFIGURATIONS,
        ] as $optionName) {
            SimpleParameterProvider::setParameter($optionName, []);
        }

        $this->freshRectorConfig = new RectorConfig();
        $this->configDumper = $this->make(ConfigDumper::class);
    }

    public function testRules(): void
    {
        $this->freshRectorConfig->paths([__DIR__ . '/Source']);
        $this->freshRectorConfig->rule(RemoveUnusedPrivateMethodRector::class);
        $this->freshRectorConfig->ruleWithConfiguration(RenameClassRector::class, [
            'App\OldClass' => 'App\NewClass',
        ]);
        $this->freshRectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
            new MethodCallRename('App\SomeClass', 'oldMethod', 'newMethod'),
        ]);

        $dump = $this->configDumper->dump();

        $this->assertSame([__DIR__ . '/Source'], $dump['paths']);
        $this->assertSame([
            [
                'class' => RemoveUnusedPrivateMethodRector::class,
                'config' => null,
            ],
            [
                'class' => RenameClassRector::class,
                'config' => [
                    'App\OldClass' => 'App\NewClass',
                ],
            ],
            [
                'class' => RenameMethodRector::class,
                'config' => [
                    [
                        '@class' => MethodCallRename::class,
                        'class' => 'App\SomeClass',
                        'oldMethod' => 'oldMethod',
                        'newMethod' => 'newMethod',
                    ],
                ],
            ],
        ], $dump['rules']);
    }

    public function testSkips(): void
    {
        $this->freshRectorConfig->rule(RemoveUnusedPrivateMethodRector::class);
        $this->freshRectorConfig->ruleWithConfiguration(RenameClassRector::class, [
            'App\OldClass' => 'App\NewClass',
        ]);
        $this->freshRectorConfig->skip([
            __DIR__ . '/Source',
            RemoveUnusedPrivateMethodRector::class => [__DIR__ . '/Fixture'],
            ConfigurableRectorInterface::class,
        ]);

        $dump = $this->configDumper->dump();

        $this->assertSame([
            [
                'path' => __DIR__ . '/Source',
            ],
            [
                'class' => RemoveUnusedPrivateMethodRector::class,
                'paths' => [__DIR__ . '/Fixture'],
            ],
            [
                'class' => ConfigurableRectorInterface::class,
            ],
            [
                'class' => RenameClassRector::class,
            ],
        ], $dump['skips']);
    }
}
