<?php

declare(strict_types=1);

namespace Rector\Tests\NodeAnalyzer\Source\AlwaysInitializedProperty;

final class EarlyReturn
{
    private ?string $name = null;

    public function __construct(string $name, bool $shouldReturn)
    {
        if ($shouldReturn) {
            return;
        }

        $this->name = $name;
    }
}
