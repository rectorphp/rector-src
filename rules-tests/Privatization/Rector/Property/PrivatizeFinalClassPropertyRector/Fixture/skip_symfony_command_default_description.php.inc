<?php

namespace Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\Fixture;

use Symfony\Component\Console\Command\Command;

final class SendWinnerEmailCommand extends Command
{
    protected static $defaultDescription = 'Send winner email variant to remaining contacts';
}
