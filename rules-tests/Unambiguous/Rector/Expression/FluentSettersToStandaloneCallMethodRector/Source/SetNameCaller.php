<?php

namespace Rector\Tests\Unambiguous\Rector\Expression\FluentSettersToStandaloneCallMethodRector\Source;

class SetNameCaller
{
    public function setName(): SurnameCaller
    {
        return new SurnameCaller();
    }
}
