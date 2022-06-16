<?php declare(strict_types=1);

final class MultiRules
{
    public function doSomething()
    {
        if (true === false) {
            return -1;
        } else {
            echo 'a statement';
        }
    }

    private function notUsed()
    {
    }
}
