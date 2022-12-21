<?php

declare(strict_types=1);

namespace Rector\Utils\ChangelogGenerator\Enum;

final class ChangelogCategory
{
    /**
     * @var string
     */
    public const NEW_FEATURES = 'New Features';

    /**
     * @var string
     */
    public const SKIPPED = 'Skipped';

    /**
     * @var string
     */
    public const BUGFIXES = 'Bugfixes';
}
