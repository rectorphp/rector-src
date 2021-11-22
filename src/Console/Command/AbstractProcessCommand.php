<?php

declare(strict_types=1);

namespace Rector\Core\Console\Command;

use Rector\Core\Configuration\ConfigurationFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractProcessCommand extends Command
{
    #[Required]
    protected ConfigurationFactory $configurationFactory;
}
