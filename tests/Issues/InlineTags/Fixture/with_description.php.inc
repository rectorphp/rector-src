<?php

use PHPUnit\Framework\TestCase;

/**
 * @copyright Example {@link https://example.com}. Additional description.
 * @todo Do this.{@link https://example.com}.
 * @covers \Tests\BarController
 */
class WithDescription extends TestCase
{
}

?>
-----
<?php

use PHPUnit\Framework\TestCase;

/**
 * @copyright Example {@link https://example.com}. Additional description.
 * @todo Do this.{@link https://example.com}.
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Tests\BarController::class)]
class WithDescription extends TestCase
{
}

?>
