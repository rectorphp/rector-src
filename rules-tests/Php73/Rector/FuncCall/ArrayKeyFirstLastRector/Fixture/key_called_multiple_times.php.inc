<?php

namespace Rector\Tests\Php73\Rector\FuncCall\ArrayKeyFirstLastRector\Fixture;

class KeyCalledMultipleTimes
{
    // @see https://3v4l.org/XfVht
    public function getFirstKeyThanLast($items)
    {
        reset($items);
        echo key($items);
        echo key($items);

        echo 'another call';

        end($items);
        echo key($items);
        echo key($items);
    }

    // @see https://3v4l.org/jhATb
    public function getLastKeyThenFirst($items2)
    {
        end($items2);
        echo key($items2);
        echo key($items2);

        echo 'another call';

        reset($items2);
        echo key($items2);
        echo key($items2);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php73\Rector\FuncCall\ArrayKeyFirstLastRector\Fixture;

class KeyCalledMultipleTimes
{
    // @see https://3v4l.org/XfVht
    public function getFirstKeyThanLast($items)
    {
        echo array_key_first($items);
        echo array_key_first($items);

        echo 'another call';
        echo array_key_last($items);
        echo array_key_last($items);
    }

    // @see https://3v4l.org/jhATb
    public function getLastKeyThenFirst($items2)
    {
        echo array_key_last($items2);
        echo array_key_last($items2);

        echo 'another call';
        echo array_key_first($items2);
        echo array_key_first($items2);
    }
}

?>
