<?php

use PHPUnit\Framework\TestCase;

/**
 * @copyright Example {@link https://example.com} some description
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
 * @copyright Example {@link https://example.com} some description
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Tests\BarController::class)]
class WithDescription extends TestCase
{
}

?>
