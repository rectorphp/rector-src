<?php

declare(strict_types=1);

namespace Rector\Tests\Php71\Rector\FuncCall\RemoveExtraParametersRector\Source;

abstract class SomeAbstractClass
{
    abstract protected function doVariadic();
}
