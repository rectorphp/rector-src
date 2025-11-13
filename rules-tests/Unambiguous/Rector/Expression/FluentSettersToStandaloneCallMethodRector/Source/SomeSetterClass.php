<?php

namespace Rector\Tests\Unambiguous\Rector\Expression\FluentSettersToStandaloneCallMethodRector\Source;

class SomeSetterClass
{
    private ?string $name = null;

    private ?string $surname = null;

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;
        return $this;
    }
}
