<?php

final class Loop
{
    /**
     * @var LoopInterface
     */
    private static $instance;

    public static function get()
    {
        if (self::$instance instanceof LoopInterface) {
            return self::$instance;
        }

        register_shutdown_function(function () {
            $error = error_get_last();
        });
    }
}
