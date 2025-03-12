<?php

namespace Rector\Tests\Php73\Rector\FuncCall\SetcookieRector\Fixture;

class SkipDynamicThirdArg
{
    public function run()
    {
        function getCookieParams(): array {
            return [
               "expires" => 12344,
               "httponly" => true
            ];
         }

         setcookie('mycookie', 'foobar', getCookieParams());
    }
}
