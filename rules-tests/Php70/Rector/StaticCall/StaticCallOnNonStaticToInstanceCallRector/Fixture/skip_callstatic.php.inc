<?php

namespace Rector\Tests\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector\Fixture;

use stdClass;

class Service
{
    public static function __callStatic($name, $arguments)
    {
        return Other::$name($arguments);
    }
}

class Other
{
    public static function getAlbum()
    {
        return new stdClass;
    }
}

class SkipMethodNotFound
{
    public function run()
    {
        return Service::getAlbum();
    }
}
