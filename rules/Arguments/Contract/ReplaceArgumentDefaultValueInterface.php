<?php

declare(strict_types=1);

namespace Rector\Arguments\Contract;

interface ReplaceArgumentDefaultValueInterface
{
    public const string ANY_VALUE_BEFORE = '*ANY_VALUE_BEFORE*';

    public function getPosition(): int;

    public function getValueBefore(): mixed;

    public function getValueAfter(): mixed;
}
