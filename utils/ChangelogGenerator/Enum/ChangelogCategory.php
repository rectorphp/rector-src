<?php

declare(strict_types=1);

namespace Rector\Utils\ChangelogGenerator\Enum;

final class ChangelogCategory
{
    /**
     * @var string
     */
    public const NEW_FEATURES = 'New Features :partying_face:';

    /**
     * @var string
     */
    public const SKIPPED = 'Skipped';

    /**
     * @var string
     */
    public const BUGFIXES = 'Bugfixes :bug:';

    /**
     * @var string
     */
    public const REMOVED = 'Removed :skull:';
}
