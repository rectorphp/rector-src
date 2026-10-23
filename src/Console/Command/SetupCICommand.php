<?php

declare(strict_types=1);

namespace Rector\Console\Command;

use Entropy\Console\Contract\CommandInterface;
use Rector\Console\ExitCode;
use Rector\Console\Style\RectorStyle;

final readonly class SetupCICommand implements CommandInterface
{
    public function __construct(
        private RectorStyle $rectorStyle
    ) {
    }

    public function getName(): string
    {
        return 'setup-ci';
    }

    public function getDescription(): string
    {
        return '[DEPRECATED] Add CI workflow to let Rector work for you';
    }

    /**
     * @api invoked via reflection by the Entropy console application
     *
     * @return ExitCode::*
     */
    public function run(): int
    {
        $this->rectorStyle->error(
            'The "setup-ci" command is deprecated and no longer generates files. Its generic template lacked caching and did not fit most setups. Add a CI workflow explicitly for your use case instead - an AI agent can tailor one to your repository.'
        );

        return ExitCode::FAILURE;
    }
}
