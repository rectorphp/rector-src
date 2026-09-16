<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Source;

final class SomeObject
{
    public string $name = 'name';

    public function getName(): string
    {
        return $this->name;
    }

    public function findName(): ?string
    {
        return $this->name;
    }
}
