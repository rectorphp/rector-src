<?php

namespace Rector\Tests\CodingStyle\Rector\Node\ConvertMultilineDocblockToSingleLineRector\Fixture;

/**
 * @see Example
 */
class SingleStatements
{
    /**
     * @var string
     */
    private $value;

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @throws \Exception
     */
    public function dangerous(): void
    {
        throw new \Exception();
    }

    public function testFunction()
    {
        /**
         * @var Foo $foo
         */
        $foo = resolve(Foo::class);

        /**
         * @phpstan-ignore variable.undefined
         */
        echo $bar;

        /**
         * @var string
         */
        $name = 'test';

        /**
         * @param int $i
         */
        if ($i > 0) {
            /**
             * @var array $data
             */
            $data = [];
        }

        /**
         * @var int $number
         */
        foreach ($items as $number) {
            // do something
        }

        /**
         * @return string
         */
        return $name;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Node\ConvertMultilineDocblockToSingleLineRector\Fixture;

/** @see Example */
class SingleStatements
{
    /** @var string */
    private $value;

    /** @return string */
    public function getValue(): string
    {
        return $this->value;
    }

    /** @param string $value */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /** @throws \Exception */
    public function dangerous(): void
    {
        throw new \Exception();
    }

    public function testFunction()
    {
        /** @var Foo $foo */
        $foo = resolve(Foo::class);

        /** @phpstan-ignore variable.undefined */
        echo $bar;

        /** @var string */
        $name = 'test';

        /** @param int $i */
        if ($i > 0) {
            /** @var array $data */
            $data = [];
        }

        /** @var int $number */
        foreach ($items as $number) {
            // do something
        }

        /** @return string */
        return $name;
    }
}

?>
