<?php

namespace Rector\Tests\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector\Fixture;

class Service
{
    public static function __callStatic($name, $arguments)
    {
    }
}

class SkipMethodNotFound
{
    public function run()
    {
        Service::getAlbum();
    }
}
