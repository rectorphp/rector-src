<?php

namespace Rector\Tests\Php80\Rector\FuncCall\Php8ResourceToObjectRector\Fixture;

/**
 * It may be a code to be compatible with php 7.x and php 8.0
 */
class SkipDoubleCheck
{
    public function run()
    {
        $ch = curl_init();
        is_resource($ch) || $ch instanceof \CurlHandle;
    }
}
