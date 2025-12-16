<?php

namespace Rector\Tests\Php85\Rector\FuncCall\ArrayKeyExistsNullToEmptyStringRector\Fixture;

final class SkipDefinedIntOrString
{
    /**
     * @var array<int, array{unique_key: int|string, label: string}>
     */
    private array $todos = [
        0 => [
            'unique_key' => 1,
            'label' => 'test 1',
        ],
        1 => [
            'unique_key' => 'fallback_todo',
            'label' => 'fallback todo 1',
        ],
    ];

    /**
     * @var array<int|string, string>
     */
    public array $selectedTodos = [];

    public function run(int $index): string
    {
  		if (!array_key_exists($index, $this->todos)) {
            return 'Todo not found';
        }

        if (!array_key_exists($this->todos[$index]['unique_key'], $this->selectedTodos)) {
            $this->selectedTodos[$this->todos[$index]['unique_key']] = $this->todos[$index]['label'];
            return 'Todo selected !';
        }

        return 'Todo already selected !';
    }
}
