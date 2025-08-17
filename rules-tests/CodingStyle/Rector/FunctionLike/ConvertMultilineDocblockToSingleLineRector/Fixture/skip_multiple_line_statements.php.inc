<?php

namespace Rector\Tests\CodingStyle\Rector\Node\ConvertMultilineDocblockToSingleLineRector\Fixture;

class SkipMultipleStatements
{
    /**
     * Some function description.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @var string
     * @see SomeClass
     */
    private $name;

    /**
     * This method does something dangerous.
     * 
     * Please be careful when using it.
     *
     * @throws \Exception
     */
    public function dangerous(): void
    {
        throw new \Exception();
    }
}
