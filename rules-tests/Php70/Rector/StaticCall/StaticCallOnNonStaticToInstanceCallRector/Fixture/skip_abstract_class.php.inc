<?php

namespace Rector\Tests\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector\Fixture;

use Rector\Tests\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector\Source\AbstractClassHello;

class SkipAbstractClassWorld extends AbstractClassHello
{
    public function say()
    {
        parent::say();
        echo "Wold!";
    }
}

class SkipAbstractClassUniverse extends SkipAbstractClassWorld
{
    public function say()
    {
        AbstractClassHello::say();
        echo "Universe!";
    }
}