<?php

namespace Symfony\Component\DependencyInjection\Argument;

if (class_exists('Symfony\\Component\\DependencyInjection\\Argument\\RewindableGenerator')) {
    return;
}

/**
 * @internal
 */
class RewindableGenerator implements \IteratorAggregate, \Countable
{
    private $generator;
    private $count;

    /**
     * @param int|callable $count
     */
    public function __construct(callable $generator, $count)
    {
        $this->generator = $generator;
        $this->count = $count;
    }

    public function getIterator(): \Traversable
    {
        $g = $this->generator;

        return $g();
    }

    public function count(): int
    {
        if (\is_callable($count = $this->count)) {
            $this->count = $count();
        }

        return $this->count;
    }
}
