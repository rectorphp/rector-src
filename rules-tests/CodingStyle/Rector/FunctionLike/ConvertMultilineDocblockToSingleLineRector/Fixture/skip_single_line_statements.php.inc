<?php

namespace Rector\Tests\CodingStyle\Rector\Node\ConvertMultilineDocblockToSingleLineRector\Fixture;

class SkipAlreadySingleLine
{
    /** @return string */
    public function getTitle(): string
    {
        return $this->title;
    }

    /** @param string $value */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /** @var string */
    private $name;
}
