<?php

namespace Rector\Tests\Php74\Rector\FuncCall\HebrevcToNl2brHebrevRector\Fixture;

final class SkipFirstClassCallable
{
    public function run()
    {
        return hebrevc(...);
    }
}

?>