<?php

namespace Rector\Tests\DeadCode\Rector\ClassLike\RemoveTypedPropertyNonMockDocblockRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipArrayDoc extends TestCase
{
    /**
     * @var string[]|mixed
     */
    private mixed $structure;

    protected function setUp(): void
    {
        parent::setUp();

        if (rand(0, 1)) {
            $this->structure = ['test'];
        } else {
            $this->structure = $this->some_mixed_function();
        }
    }

	private function some_mixed_function(): mixed
	{
		return null;
	}
}
