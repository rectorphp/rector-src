<?php
declare(strict_types=1);

namespace Rector\Tests\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector\Source;

abstract class AbstractClassHello
{
    public function say()
    {
        echo "Hello ";
    }
}
