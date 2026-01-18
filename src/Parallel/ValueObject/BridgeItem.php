<?php

declare(strict_types=1);

namespace Rector\Parallel\ValueObject;

/**
 * @api
 * Helpers constant for passing constant names around
 */
final class BridgeItem
{
    public const string LINE = 'line';

    public const string MESSAGE = 'message';

    public const string RELATIVE_FILE_PATH = 'relative_file_path';

    public const string ABSOLUTE_FILE_PATH = 'absolute_file_path';

    public const string DIFF = 'diff';

    public const string DIFF_CONSOLE_FORMATTED = 'diff_console_formatted';

    public const string APPLIED_RECTORS = 'applied_rectors';

    public const string RECTOR_CLASS = 'rector_class';

    public const string RECTORS_WITH_LINE_CHANGES = 'rectors_with_line_changes';
}
