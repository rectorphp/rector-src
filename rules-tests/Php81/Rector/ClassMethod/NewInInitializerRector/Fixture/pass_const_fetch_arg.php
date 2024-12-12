<?php

namespace Rector\Tests\Php81\Rector\ClassMethod\NewInInitializerRector\Fixture;

use DateTime;

const NOW = 'now';

class PassConstFetchArg
{
    private DateTime $dateTime;

    public function __construct(
        ?DateTime $dateTime = null
    ) {
        $this->dateTime = $dateTime ?? new DateTime(NOW);
    }
}

?>
