<?php

declare(strict_types=1);

namespace Rector\PhpDocParser\ValueObject;

/**
 * @api
 */
final class AttributeKey
{
    /**
     * Used in php-parser, do not change
     */
    public const string KIND = 'kind';

    /**
     * Used by php-parser, do not change
     */
    public const string COMMENTS = 'comments';

    /**
     * PHPStan @api Used in PHPStan for printed node content. Useful for printing error messages without need to reprint
     * it again.
     */
    public const string PHPSTAN_CACHE_PRINTER = 'phpstan_cache_printer';

    public const string ASSIGNED_TO = 'assigned_to';

    public const string NULLSAFE_CHECKED = 'nullsafe_checked';

    /**
     * PHPStan @api
     */
    public const string PARENT_STMT_TYPES = 'parentStmtTypes';
}
