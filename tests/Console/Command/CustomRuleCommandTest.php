<?php

namespace Rector\Tests\Console\Command;

use Nette\Utils\FileSystem;
use Rector\Console\Command\CustomRuleCommand;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class CustomRuleCommandTest extends AbstractLazyTestCase
{
    private const TEST_OUTPUT_DIRECTORY = 'tests/Console/Command/CustomRuleCommand';

    private CustomRuleCommand $customRuleCommand;

    protected function setUp(): void
    {
        $this->customRuleCommand = $this->make(CustomRuleCommand::class);
    }

    public function test(): void
    {
        $ruleName = 'SomeCustomRuleRector';
        $rulesDirectory = self::TEST_OUTPUT_DIRECTORY . '/src/Rector/Test';
        $testsDirectory = self::TEST_OUTPUT_DIRECTORY . '/src/Rector';

        $commandTester = new CommandTester($this->customRuleCommand);
        $commandTester->execute([
            '--name' => $ruleName,
            '--with-composer-changes' => false,
            '--with-phpunit-changes' => false,
            '--with-rules-dir' => $rulesDirectory,
            '--with-tests-dir' => $testsDirectory,
        ]);

        $currentDirectory = getcwd();
        $this->assertNotEmpty(FileSystem::read(sprintf(
            '%s/%s/%s.php',
            $currentDirectory,
            $rulesDirectory,
            $ruleName
        )));
        $this->assertNotEmpty(
            FileSystem::read(sprintf(
                '%s/%s/%s/Fixture/some_class.php.inc',
                $currentDirectory,
                $testsDirectory,
                $ruleName
            ))
        );
        $this->assertNotEmpty(
            FileSystem::read(sprintf(
                '%s/%s/%s/config/configured_rule.php',
                $currentDirectory,
                $testsDirectory,
                $ruleName
            ))
        );
        $this->assertNotEmpty(
            FileSystem::read(sprintf(
                '%s/%s/%s/%sTest.php',
                $currentDirectory,
                $testsDirectory,
                $ruleName,
                $ruleName
            ))
        );

        $this->cleanup($currentDirectory);
    }

    public function testWithRulesAndTestsNamespace(): void
    {
        $ruleName = 'SomeCustomRuleRector';
        $ruleDirectory = self::TEST_OUTPUT_DIRECTORY . '/utils/Rector/src/Rector';
        $testsDirectory = self::TEST_OUTPUT_DIRECTORY . '/utils/Rector/tests/Rector';
        $rulesNamespace = 'RectorLaravel\Custom';
        $testsNamespace = 'RectorLaravel\Tests\Custom';

        $commandTester = new CommandTester($this->customRuleCommand);
        $commandTester->execute([
            '--name' => $ruleName,
            '--with-composer-changes' => false,
            '--with-phpunit-changes' => false,
            '--with-rules-dir' => $ruleDirectory,
            '--with-tests-dir' => $testsDirectory,
            '--with-rules-namespace' => $rulesNamespace,
            '--with-tests-namespace' => $testsNamespace,
        ]);

        $currentDirectory = getcwd();
        $this->assertStringContainsString(
            sprintf('namespace %s;', $rulesNamespace),
            FileSystem::read(sprintf('%s/%s/%s.php', $currentDirectory, $ruleDirectory, $ruleName)),
        );
        $this->assertStringContainsString(
            sprintf('@see \\%s\\%s\\%sTest', $testsNamespace, $ruleName, $ruleName),
            FileSystem::read(sprintf('%s/%s/%s.php', $currentDirectory, $ruleDirectory, $ruleName)),
        );
        $this->assertStringContainsString(
            sprintf('namespace %s\\%s;', $testsNamespace, $ruleName),
            FileSystem::read(sprintf('%s/%s/%s/%sTest.php', $currentDirectory, $testsDirectory, $ruleName, $ruleName)),
        );

        $configFile = FileSystem::read(
            sprintf('%s/%s/%s/config/configured_rule.php', $currentDirectory, $testsDirectory, $ruleName)
        );
        $this->assertStringContainsString(sprintf('$rectorConfig->rule(%s::class);', $ruleName), $configFile);
        $this->assertStringContainsString(sprintf('use %s\\%s;', $rulesNamespace, $ruleName), $configFile);

        $this->assertStringContainsString(
            sprintf('namespace %s\\%s\\Fixture;', $testsNamespace, $ruleName),
            FileSystem::read(
                sprintf('%s/%s/%s/Fixture/some_class.php.inc', $currentDirectory, $testsDirectory, $ruleName)
            ),
        );

        $this->cleanup($currentDirectory);
    }

    private function cleanup(false|string $currentDirectory): void
    {
        FileSystem::delete($currentDirectory . '/' . self::TEST_OUTPUT_DIRECTORY);
    }
}
