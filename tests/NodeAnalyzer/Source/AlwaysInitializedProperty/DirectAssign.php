<?php

declare(strict_types=1);

namespace Rector\Tests\NodeAnalyzer\Source\AlwaysInitializedProperty;

final class DirectAssign
{
    private ?string $name = null;

    private ?string $surname = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
