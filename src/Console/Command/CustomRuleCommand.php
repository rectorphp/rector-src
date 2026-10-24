<?php

declare(strict_types=1);

namespace Rector\Console\Command;

use Entropy\Console\Contract\CommandInterface;
use Rector\Console\ExitCode;
use Rector\Console\Style\RectorStyle;

final readonly class CustomRuleCommand implements CommandInterface
{
    public function __construct(
        private RectorStyle $rectorStyle
    ) {
    }

    public function getName(): string
    {
        return 'custom-rule';
    }

    public function getDescription(): string
    {
        return '[DEPRECATED] Create base of local custom rule with tests';
    }

    /**
     * @api invoked via reflection by the Entropy console application
     *
     * @return ExitCode::*
     */
    public function run(): int
    {
        $this->rectorStyle->error(
            'The "custom-rule" command is deprecated and no longer generates files. Use an AI agent to scaffold a custom rule instead - it handles the setup faster and with less guesswork.'
        );

        return ExitCode::FAILURE;
    }
}
