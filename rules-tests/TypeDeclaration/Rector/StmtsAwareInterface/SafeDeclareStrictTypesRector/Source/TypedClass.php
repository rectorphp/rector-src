<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Source;

final class TypedClass
{
    public int $count = 0;

    public static int $staticCount = 0;

    public function __construct(
        private string $name,
        private int $value
    ) {
    }

    public function add(int $a, int $b): int
    {
        return $a + $b;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function process(self $other): void
    {
    }

    public static function format(string $input): string
    {
        return trim($input);
    }
}
