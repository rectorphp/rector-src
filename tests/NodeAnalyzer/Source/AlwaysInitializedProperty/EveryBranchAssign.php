<?php

declare(strict_types=1);

namespace Rector\Tests\NodeAnalyzer\Source\AlwaysInitializedProperty;

final class EveryBranchAssign
{
    private ?string $name = null;

    public function __construct(int $type)
    {
        if ($type === 1) {
            $this->name = 'one';
        } elseif ($type === 2) {
            $this->name = 'two';
        } else {
            $this->name = 'many';
        }
    }
}
