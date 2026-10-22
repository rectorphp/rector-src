<?php

declare(strict_types=1);

use Rector\Utils\PHPStan\Tests\Rule\RegisterRectorRule\Source\RegisteredRector;

// mimics a set/level config; the rule only checks the "::class" reference is present
return [RegisteredRector::class];
