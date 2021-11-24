<?php

namespace Rector\Core\Tests\Issues\IssueRefreshScope\Fixture;

final class ForeachIterable
{
    public function run(): void
    {
        $itemsByName = $this->getItemsByName();
        foreach ($itemsByName as $values) {
            if (count($values)) {
                continue;
            }
        }
    }

    /**
     * @return array<string, string[]>
     */
    private function getItemsByName(): array
    {
        return [
            'name' => ['Tom', 'Matthias'],
        ];
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\IssueRefreshScope\Fixture;

final class ForeachIterable
{
    public function run(): void
    {
        $itemsByName = $this->getItemsByName();
        foreach ($itemsByName as $items) {
            if (count($items)) {
                continue;
            }
        }
    }

    /**
     * @return array<string, string[]>
     */
    private function getItemsByName(): array
    {
        return [
            'name' => ['Tom', 'Matthias'],
        ];
    }
}

?>
