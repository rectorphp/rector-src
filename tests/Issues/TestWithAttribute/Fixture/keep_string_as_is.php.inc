<?php

namespace Rector\Tests\Issues\DoubleRun\TestWithAttribute;

class KeepStringAsIs extends \PHPUnit\Framework\TestCase
{
    /**
     * @testWith ["2", 2, "null", "true", "false", true, false]
     *           ["benjamin.sisko@ds9.example.com"]
     */
    public function testSomething(string $userId): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\DoubleRun\TestWithAttribute;

class KeepStringAsIs extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\TestWith(['2', 2, 'null', 'true', 'false', true, false])]
    #[\PHPUnit\Framework\Attributes\TestWith(['benjamin.sisko@ds9.example.com'])]
    public function testSomething(string $userId): void
    {
    }
}

?>
