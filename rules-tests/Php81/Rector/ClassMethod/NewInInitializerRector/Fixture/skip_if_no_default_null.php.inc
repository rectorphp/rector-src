<?php

namespace Rector\Tests\Php81\Rector\ClassMethod\NewInInitializerRector\Fixture;

use Php81\Rector\ClassMethod\NewInInitializerRector\Source\Logger\DefaultLogger;
use Php81\Rector\ClassMethod\NewInInitializerRector\Source\Logger\NullLogger;

final class SkipIfNoDefaultNull
{
    private DefaultLogger $logger;

    public function __construct(
        ?DefaultLogger $logger,
    ) {
        $this->logger = $logger ?? new NullLogger();
    }
}
