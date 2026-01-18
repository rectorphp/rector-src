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
     * Version-based set provider
     */
    public const string TWIG = 'twig';

    /**
     * Version-based set provider
     */
    public const string PHPUNIT = 'phpunit';

    /**
     * Version-based set provider
     */
    public const string DOCTRINE = 'doctrine';

    /**
     * Version-based set provider
     */
    public const string SYMFONY = 'symfony';

    /**
     * Version-based set provider
     */
    public const string NETTE_UTILS = 'nette-utils';

    /**
     * Version-based set provider
     */
    public const string LARAVEL = 'laravel';

    public const string ATTRIBUTES = 'attributes';
}
