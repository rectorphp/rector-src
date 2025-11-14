<?php

namespace Rector\Tests\Unambiguous\Rector\Expression\FluentSettersToStandaloneCallMethodRector\Source;

class SurnameCaller
{
    public function setSurname(): self
    {
        return $this;
    }
}
