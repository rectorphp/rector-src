<?php

namespace Rector\Tests\Php81\Rector\ClassMethod\NewInInitializerRector\Fixture;

class SkipAssignInInnerClass
{
    private Logger $logger;

    public function __construct(
        ?Logger $logger = null,
    ) {
        new class {
            public $logger;

            public function init()
            {
                $this->logger = $logger ?? new NullLogger;
            }
        };
    }
}