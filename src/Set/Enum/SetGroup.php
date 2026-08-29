<?php

declare(strict_types=1);

namespace Rector\Set\Enum;

/**
 * @api used in sets
 */
final class SetGroup
{
    public const string CORE = 'core';

    public const string PHP = 'php';

    /**
     * @deprecated Use composer-based.php set instead
     * Version-based set provider
     */
    public const string LARAVEL = 'laravel';

    /**
     * @deprecated Use composer-based.php set instead
     * Version-based set provider
     */
    public const string DRUPAL = 'drupal';

    public const string ATTRIBUTES = 'attributes';
}
