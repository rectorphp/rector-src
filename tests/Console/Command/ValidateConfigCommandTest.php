<?php

declare(strict_types=1);

namespace Rector\Tests\Console\Command;

use Nette\Utils\Json;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Configuration\VendorMissAnalyseGuard;
use Rector\Console\Command\ValidateConfigCommand;
use Rector\Console\ExitCode;
use Rector\Php85\Rector\FuncCall\OrdSingleByteRector;
use Rector\Reporting\DeprecatedRulesReporter;
use Rector\Reporting\MissConfigurationReporter;
use Rector\Reporting\UnusedSkipResolver;
use Rector\Skipper\SkipCriteriaResolver\SkippedClassResolver;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\Console\Command\Source\DeprecatedFixtureRule;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Tester\CommandTester;

final class ValidateConfigCommandTest extends AbstractLazyTestCase
{
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        parent::setUp();

        $symfonyStyle = new SymfonyStyle(new ArrayInput([]), new BufferedOutput());

        $validateConfigCommand = new ValidateConfigCommand(
            $symfonyStyle,
            new DeprecatedRulesReporter($symfonyStyle, []),
            new MissConfigurationReporter(
                $symfonyStyle,
                new VendorMissAnalyseGuard(),
                $this->make(UnusedSkipResolver::class),
            ),
            $this->make(SkippedClassResolver::class),
        );

        $this->commandTester = new CommandTester($validateConfigCommand);
    }

    protected function tearDown(): void
    {
        SimpleParameterProvider::setParameter(Option::REGISTERED_RECTOR_RULES, []);
    }

    public function testFailsOnDeprecatedRegisteredRule(): void
    {
        SimpleParameterProvider::setParameter(Option::REGISTERED_RECTOR_RULES, [DeprecatedFixtureRule::class]);

        $this->assertSame(ExitCode::FAILURE, $this->commandTester->execute([]));
    }

    public function testSucceedsOnCleanConfig(): void
    {
        SimpleParameterProvider::setParameter(Option::REGISTERED_RECTOR_RULES, [OrdSingleByteRector::class]);

        $this->assertSame(ExitCode::SUCCESS, $this->commandTester->execute([]));
    }

    public function testJsonOutputOnCleanConfig(): void
    {
        SimpleParameterProvider::setParameter(Option::REGISTERED_RECTOR_RULES, [OrdSingleByteRector::class]);

        ob_start();
        $exitCode = $this->commandTester->execute([
            '--output-format' => 'json',
        ]);
        $json = (string) ob_get_clean();

        $this->assertSame(ExitCode::SUCCESS, $exitCode);
        $this->assertSame([
            'valid' => true,
            'issue_count' => 0,
        ], Json::decode($json, forceArrays: true));
    }

    public function testJsonOutputOnDeprecatedRegisteredRule(): void
    {
        SimpleParameterProvider::setParameter(Option::REGISTERED_RECTOR_RULES, [DeprecatedFixtureRule::class]);

        ob_start();
        $exitCode = $this->commandTester->execute([
            '--output-format' => 'json',
        ]);
        $json = (string) ob_get_clean();

        $this->assertSame(ExitCode::FAILURE, $exitCode);
        $this->assertSame([
            'valid' => false,
            'issue_count' => 1,
        ], Json::decode($json, forceArrays: true));
    }
}
