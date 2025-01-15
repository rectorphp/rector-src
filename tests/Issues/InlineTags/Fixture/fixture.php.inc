<?php

use PHPUnit\Framework\TestCase;

/**
 * @copyright Example {@link https://example.com}
 * @covers \Tests\BarController
 */
class BarController extends TestCase
{
}

?>
-----
<?php

use PHPUnit\Framework\TestCase;

/**
 * @copyright Example {@link https://example.com}
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Tests\BarController::class)]
class BarController extends TestCase
{
}

?>
