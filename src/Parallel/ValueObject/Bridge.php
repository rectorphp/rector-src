<?php

declare(strict_types=1);

namespace Rector\Parallel\ValueObject;

/**
 * @enum
 */
final class Bridge
{
    public const string FILE_DIFFS = 'file_diffs';

    public const string SYSTEM_ERRORS = 'system_errors';

    public const string SYSTEM_ERRORS_COUNT = 'system_errors_count';

    public const string FILES = 'files';

    public const string FILES_COUNT = 'files_count';

    public const string TOTAL_CHANGED = 'total_changed';
}
