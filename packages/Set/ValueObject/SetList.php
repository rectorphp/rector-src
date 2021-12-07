<?php

declare(strict_types=1);

namespace Rector\Set\ValueObject;

use Rector\Set\Contract\SetListInterface;

final class SetList implements SetListInterface
{
    /**
     * @var string
     */
    final public const DEFLUENT = __DIR__ . '/../../../config/set/defluent.php';

    /**
     * @var string
     */
    final public const ACTION_INJECTION_TO_CONSTRUCTOR_INJECTION = __DIR__ . '/../../../config/set/action-injection-to-constructor-injection.php';

    /**
     * @var string
     */
    final public const CODE_QUALITY = __DIR__ . '/../../../config/set/code-quality.php';

    /**
     * @deprecated Use only/directly CODE_QUALITY instead
     * @var string
     */
    final public const CODE_QUALITY_STRICT = __DIR__ . '/../../../config/set/code-quality-strict.php';

    /**
     * @var string
     */
    final public const CODING_STYLE = __DIR__ . '/../../../config/set/coding-style.php';

    /**
     * @var string
     */
    final public const DEAD_CODE = __DIR__ . '/../../../config/set/dead-code.php';

    /**
     * @var string
     */
    final public const FLYSYSTEM_20 = __DIR__ . '/../../../config/set/flysystem-20.php';

    /**
     * @var string
     */
    final public const FRAMEWORK_EXTRA_BUNDLE_40 = __DIR__ . '/../../../config/set/framework-extra-bundle-40.php';

    /**
     * @var string
     */
    final public const FRAMEWORK_EXTRA_BUNDLE_50 = __DIR__ . '/../../../config/set/framework-extra-bundle-50.php';

    /**
     * @var string
     */
    final public const GMAGICK_TO_IMAGICK = __DIR__ . '/../../../config/set/gmagick_to_imagick.php';

    /**
     * @var string
     */
    final public const MONOLOG_20 = __DIR__ . '/../../../config/set/monolog20.php';

    /**
     * @var string
     */
    final public const MYSQL_TO_MYSQLI = __DIR__ . '/../../../config/set/mysql-to-mysqli.php';

    /**
     * @var string
     */
    final public const NAMING = __DIR__ . '/../../../config/set/naming.php';

    /**
     * @var string
     */
    final public const ORDER = __DIR__ . '/../../../config/set/order.php';

    /**
     * @var string
     */
    final public const PHPSPEC_30 = __DIR__ . '/../../../config/set/phpspec30.php';

    /**
     * @var string
     */
    final public const PHPSPEC_40 = __DIR__ . '/../../../config/set/phpspec40.php';

    /**
     * @var string
     */
    final public const PHPSPEC_TO_PHPUNIT = __DIR__ . '/../../../config/set/phpspec-to-phpunit.php';

    /**
     * @var string
     */
    final public const PHP_52 = __DIR__ . '/../../../config/set/php52.php';

    /**
     * @var string
     */
    final public const PHP_53 = __DIR__ . '/../../../config/set/php53.php';

    /**
     * @var string
     */
    final public const PHP_54 = __DIR__ . '/../../../config/set/php54.php';

    /**
     * @var string
     */
    final public const PHP_55 = __DIR__ . '/../../../config/set/php55.php';

    /**
     * @var string
     */
    final public const PHP_56 = __DIR__ . '/../../../config/set/php56.php';

    /**
     * @var string
     */
    final public const PHP_70 = __DIR__ . '/../../../config/set/php70.php';

    /**
     * @var string
     */
    final public const PHP_71 = __DIR__ . '/../../../config/set/php71.php';

    /**
     * @var string
     */
    final public const PHP_72 = __DIR__ . '/../../../config/set/php72.php';

    /**
     * @var string
     */
    final public const PHP_73 = __DIR__ . '/../../../config/set/php73.php';

    /**
     * @var string
     */
    final public const PHP_74 = __DIR__ . '/../../../config/set/php74.php';

    /**
     * @var string
     */
    final public const PHP_80 = __DIR__ . '/../../../config/set/php80.php';

    /**
     * @var string
     */
    final public const PHP_81 = __DIR__ . '/../../../config/set/php81.php';

    /**
     * @var string
     */
    final public const PRIVATIZATION = __DIR__ . '/../../../config/set/privatization.php';

    /**
     * @var string
     */
    final public const PSR_4 = __DIR__ . '/../../../config/set/psr-4.php';

    /**
     * @var string
     */
    final public const SAFE_07 = __DIR__ . '/../../../config/set/safe07.php';

    /**
     * @var string
     */
    final public const TYPE_DECLARATION = __DIR__ . '/../../../config/set/type-declaration.php';

    /**
     * @var string
     */
    final public const TYPE_DECLARATION_STRICT = __DIR__ . '/../../../config/set/type-declaration-strict.php';

    /**
     * @var string
     */
    final public const UNWRAP_COMPAT = __DIR__ . '/../../../config/set/unwrap-compat.php';

    /**
     * @var string
     */
    final public const EARLY_RETURN = __DIR__ . '/../../../config/set/early-return.php';
}
