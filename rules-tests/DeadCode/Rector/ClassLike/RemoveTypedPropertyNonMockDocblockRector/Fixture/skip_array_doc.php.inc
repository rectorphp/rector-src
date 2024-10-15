<?php

namespace Rector\Tests\DeadCode\Rector\ClassLike\RemoveTypedPropertyNonMockDocblockRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipArrayDoc extends TestCase
{
    /**
     * @var array<string, array<string, list<mixed>>>|array<string, array<string, string>>|array<string, list<mixed>>|array<string, mixed>|array<string, string>|mixed
     */
    private array $structure;

    protected function setUp(): void
    {
        parent::setUp();

        $this->structure = [
            'foo' => [
                'bar' => 'Once upon a midnight dreary',
                'baz' => 'While I pondered weak and weary',
            ],
            'boo' => [
                'far' => 'Upon a tome of long-forgotten lore',
                'faz' => 'There came a tapping up on the door',
            ],
            'AnEmptyFolder' => [],
            'simpleFile'    => 'A tap-tap-tapping upon my door',
            '.hidden'       => 'There is no spoon',
        ];
    }
}
