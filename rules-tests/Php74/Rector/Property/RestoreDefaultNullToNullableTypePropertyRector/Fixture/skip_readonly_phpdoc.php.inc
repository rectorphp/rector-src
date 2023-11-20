<?php

namespace Rector\Tests\Php74\Rector\Property\RestoreDefaultNullToNullableTypePropertyRector\Fixture;

final class SkipReadonlyPhpdoc
{
    public const LEVEL_NONE = 'none';

    private const LEVEL_SHORT_TERM = 'short-term';

    public const LEVELS = [
        self::LEVEL_NONE => self::LEVEL_NONE,
        self::LEVEL_SHORT_TERM => self::LEVEL_SHORT_TERM,
    ];

    /**
     * @var self::LEVEL_*|null
     *
     * @readonly
     */
    private ?string $level;

    private function __construct(string $level)
    {
        if (isset(self::LEVELS[$level])) {
            $this->level = self::LEVELS[$level];
        } else {
            $this->level = null;
        }
    }

}
