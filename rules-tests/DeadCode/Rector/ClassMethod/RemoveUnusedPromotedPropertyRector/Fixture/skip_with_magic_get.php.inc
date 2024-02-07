<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector\Fixture;

class SkipWithMagicGet
{
    private function __construct(
        private readonly int $id,
        private string $name,
        private array $email,
        private string $country,
        private readonly ?Time $created_at,
        private readonly ?Time $updated_at,
    ) {
    }

    public function __get(string $name)
    {
        if (! property_exists($this, $name)) {
            throw new \LogicException('No such property: ' . $name);
        }

        return $this->{$name};
    }
}