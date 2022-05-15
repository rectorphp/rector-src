<?php

final class AlwaysTrue
{
    public function run()
    {
        if (1 === 1) {
            return 'yes';
        }

        return 'no';
    }
}
