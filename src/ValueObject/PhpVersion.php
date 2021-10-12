<?php

declare(strict_types=1);

namespace Rector\Core\ValueObject;

use MyCLabs\Enum\Enum;

final class PhpVersion extends Enum
{
    /**
     * @api
     * @var int
     */
    public const PHP_52 = 50200;

    /**
     * @api
     * @var int
     */
    public const PHP_53 = 50300;

    /**
     * @api
     * @var int
     */
    public const PHP_54 = 50400;

    /**
     * @api
     * @var int
     */
    public const PHP_55 = 50500;

    /**
     * @api
     * @var int
     */
    public const PHP_56 = 50600;

    /**
     * @api
     * @var int
     */
    public const PHP_70 = 70000;

    /**
     * @api
     * @var int
     */
    public const PHP_71 = 70100;

    /**
     * @api
     * @var int
     */
    public const PHP_72 = 70200;

    /**
     * @api
     * @var int
     */
    public const PHP_73 = 70300;

    /**
     * @api
     * @var int
     */
    public const PHP_74 = 70400;

    /**
     * @api
     * @var int
     */
    public const PHP_80 = 80000;

    /**
     * @api
     * @var int
     */
    public const PHP_81 = 81000;

    /**
     * @api
     * @var int
     */
    public const PHP_10 = 100000;
}
