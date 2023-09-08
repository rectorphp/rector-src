<?php

declare(strict_types=1);

namespace Rector\Core\Console;

use Composer\XdebugHandler\XdebugHandler;
use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Rector\Core\Application\VersionResolver;
use Rector\Core\Configuration\Option;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\Assert\Assert;

final class ConsoleApplication extends Application
{
    /**
     * @var string
     */
    private const NAME = 'Rector';

    /**
     * @param Command[] $commands
     */
    public function __construct(array $commands)
    {
        parent::__construct(self::NAME, VersionResolver::PACKAGE_VERSION);

        Assert::notEmpty($commands);
        Assert::allIsInstanceOf($commands, Command::class);

        $this->addCommands($commands);

        // run this command, if no command name is provided
        $this->setDefaultCommand('process');
    }

    public function doRun(InputInterface $input, OutputInterface $output): int
    {
        // @fixes https://github.com/rectorphp/rector/issues/2205
        $isXdebugAllowed = $input->hasParameterOption('--xdebug');
        if (! $isXdebugAllowed) {
            $xdebugHandler = new XdebugHandler('rector');
            $xdebugHandler->check();
            unset($xdebugHandler);
        }

        $shouldFollowByNewline = false;

        // skip in this case, since generate content must be clear from meta-info
        if ($this->shouldPrintMetaInformation($input)) {
            $output->writeln($this->getLongVersion());
            $shouldFollowByNewline = true;
        }

        if ($shouldFollowByNewline) {
            $output->write(PHP_EOL);
        }

        return parent::doRun($input, $output);
    }

    protected function getDefaultInputDefinition(): InputDefinition
    {
        $defaultInputDefinition = parent::getDefaultInputDefinition();

        $this->removeUnusedOptions($defaultInputDefinition);
        $this->addCustomOptions($defaultInputDefinition);

        return $defaultInputDefinition;
    }

    private function shouldPrintMetaInformation(InputInterface $input): bool
    {
        $hasNoArguments = $input->getFirstArgument() === null;
        if ($hasNoArguments) {
            return false;
        }

        $hasVersionOption = $input->hasParameterOption('--version');
        if ($hasVersionOption) {
            return false;
        }

        $outputFormat = $input->getParameterOption(['-o', '--output-format']);
        return $outputFormat === ConsoleOutputFormatter::NAME;
    }

    private function removeUnusedOptions(InputDefinition $inputDefinition): void
    {
        $options = $inputDefinition->getOptions();

        unset($options['quiet'], $options['no-interaction']);

        $inputDefinition->setOptions($options);
    }

    private function addCustomOptions(InputDefinition $inputDefinition): void
    {
        $inputDefinition->addOption(new InputOption(
            Option::CONFIG,
            'c',
            InputOption::VALUE_REQUIRED,
            'Path to config file',
            $this->getDefaultConfigPath()
        ));

        $inputDefinition->addOption(new InputOption(
            Option::DEBUG,
            null,
            InputOption::VALUE_NONE,
            'Enable debug verbosity (-vvv)'
        ));

        $inputDefinition->addOption(new InputOption(
            Option::XDEBUG,
            null,
            InputOption::VALUE_NONE,
            'Allow running xdebug'
        ));

        $inputDefinition->addOption(new InputOption(
            Option::CLEAR_CACHE,
            null,
            InputOption::VALUE_NONE,
            'Clear cache'
        ));
    }

    private function getDefaultConfigPath(): string
    {
        return getcwd() . '/rector.php';
    }
}
