<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

final class SkipDimFetchAsRisky
{
    /**
     * @var string
     */
    private $key;

    protected $writeOnly;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function buildData(): array
    {
        $this->writeOnly[$this->key] = 10000;
    }
}
