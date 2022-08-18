<?php

namespace Carbon;

/**
 * @property-read int $dayOfWeek
 */
final class Carbon {
     /**
     * @param int $day
     *
     * @return bool
     */
    public function isDayOfWeek($day)
    {
        return $day === 1;
    }

    public function __get()
    {

    }
}

final class DemoFile
{
    public $weekday;
    public function run()
    {
        $carbon = new Carbon();
        if ($this->weekday === $carbon->dayOfWeek) {
            return 1;
        }

        return 0;
    }
}
