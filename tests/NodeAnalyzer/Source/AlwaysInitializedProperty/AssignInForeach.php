<?php

declare(strict_types=1);

namespace Rector\Tests\NodeAnalyzer\Source\AlwaysInitializedProperty;

final class AssignInForeach
{
    private ?string $name = null;

    /**
     * @param string[] $names
     */
    public function __construct(array $names)
    {
        foreach ($names as $name) {
            $this->name = $name;
        }
    }
}
