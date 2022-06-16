<?php declare(strict_types=1);

final class AlwaysTrue
{
    public function run()
    {
        if (1 === 1) {
        }

        return 'no';
    }
}
