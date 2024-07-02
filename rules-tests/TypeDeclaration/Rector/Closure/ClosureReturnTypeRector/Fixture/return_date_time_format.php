<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector\Fixture;

final class ReturnDateTimeFormat
{
    public function run()
    {
        function () {
            $dt = new \DateTime('now');
            return $dt->format('Y-m-d');
        };
    }
}

?>
