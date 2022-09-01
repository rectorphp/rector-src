<?php

declare(strict_types=1);

namespace Rector\Core\Console\Style;

use Rector\Core\Util\Reflection\PrivatesAccessor;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

final class RectorConsoleOutputStyleFactory
{
    public function __construct(private PrivatesAccessor $privatesAccessor)
    {
    }

    public function create(): RectorConsoleOutputStyle
    {
        $argvInput = new ArgvInput();
        $consoleOutput = new ConsoleOutput();

        // to configure all -v, -vv, -vvv options without memory-lock to Application run() arguments
        $this->privatesAccessor->callPrivateMethod(new Application(), 'configureIO', [$argvInput, $consoleOutput]);

        // --debug is called
        if ($argvInput->hasParameterOption('--debug')) {
            $consoleOutput->setVerbosity(OutputInterface::VERBOSITY_DEBUG);
        }

        return new RectorConsoleOutputStyle($argvInput, $consoleOutput);
    }
}
