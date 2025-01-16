<?php

use PHPUnit\Framework\TestCase;

/**
 * @copyright Some Value. Something.({@link https://example.com}).
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
 * @copyright Some Value. {@link https://example.com}.
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Tests\BarController::class)]
class WithDescription extends TestCase
{
}

?>
