<?php

declare(strict_types=1);

use Rector\Utils\PHPStan\Tests\Rule\RegisterRelatedPolyfillRectorRule\Source\RegisteredPolyfillRector;

// mimics config/set/php-polyfills.php; the rule only checks the "::class" reference is present
return [RegisteredPolyfillRector::class];
