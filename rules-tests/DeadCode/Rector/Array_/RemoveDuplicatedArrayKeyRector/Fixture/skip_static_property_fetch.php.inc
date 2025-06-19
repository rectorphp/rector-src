<?php

namespace Rector\Tests\DeadCode\Rector\Array_\RemoveDuplicatedArrayKeyRector\Fixture;

class SkipStaticPropertyFetch
{
    private static $foo = 'foo';

    public function lists()
    {
        $items = [
            self::$foo => $this->run(),
            self::$foo => $this->run(),
        ];
    }

    private function run()
    {
        static::$foo = rand();

        return 'A';
    }
}

?>
