<?php

namespace Rector\Tests\Php85\Rector\FuncCall\ArrayKeyExistsNullToEmptyStringRector\Fixture;

final class SkipDefinedIntOrString
{
    /**
     * @var array<string|int, bool>
     */
    private array $shownTodos = [
        0 => false,
        'fallback_todo' => true,
    ];

    public function run(string|int $index): void
    {
  		if (! array_key_exists($index, $this->shownTodos)) {
            return;
        }

        $this->shownTodos[$index] = true;
    }
}
