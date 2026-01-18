<?php

declare(strict_types=1);

namespace Rector\Set\ValueObject;

/**
 * @api
 */
final class SetList
{
    /**
     * @internal
     */
    public const string PHP_POLYFILLS = __DIR__ . '/../../../config/set/php-polyfills.php';

    public const string CODE_QUALITY = __DIR__ . '/../../../config/set/code-quality.php';

    public const string CODING_STYLE = __DIR__ . '/../../../config/set/coding-style.php';

    public const string DEAD_CODE = __DIR__ . '/../../../config/set/dead-code.php';

    /**
     * @deprecated As too strict and not practical. Use code quality and coding style sets instead.
     */
    public const string STRICT_BOOLEANS = __DIR__ . '/../../../config/set/strict-booleans.php';

    public const string GMAGICK_TO_IMAGICK = __DIR__ . '/../../../config/set/gmagick-to-imagick.php';

    public const string NAMING = __DIR__ . '/../../../config/set/naming.php';

    /**
     * Opinionated rules that match rector coding standard
     */
    public const string RECTOR_PRESET = __DIR__ . '/../../../config/set/rector-preset.php';

    public const string PHP_52 = __DIR__ . '/../../../config/set/php52.php';

    public const string PHP_53 = __DIR__ . '/../../../config/set/php53.php';

    public const string PHP_54 = __DIR__ . '/../../../config/set/php54.php';

    public const string PHP_55 = __DIR__ . '/../../../config/set/php55.php';

    public const string PHP_56 = __DIR__ . '/../../../config/set/php56.php';

    public const string PHP_70 = __DIR__ . '/../../../config/set/php70.php';

    public const string PHP_71 = __DIR__ . '/../../../config/set/php71.php';

    public const string PHP_72 = __DIR__ . '/../../../config/set/php72.php';

    public const string PHP_73 = __DIR__ . '/../../../config/set/php73.php';

    public const string PHP_74 = __DIR__ . '/../../../config/set/php74.php';

    public const string PHP_80 = __DIR__ . '/../../../config/set/php80.php';

    public const string PHP_81 = __DIR__ . '/../../../config/set/php81.php';

    public const string PHP_82 = __DIR__ . '/../../../config/set/php82.php';

    public const string PHP_83 = __DIR__ . '/../../../config/set/php83.php';

    public const string PHP_84 = __DIR__ . '/../../../config/set/php84.php';

    public const string PHP_85 = __DIR__ . '/../../../config/set/php85.php';

    public const string PRIVATIZATION = __DIR__ . '/../../../config/set/privatization.php';

    public const string TYPE_DECLARATION = __DIR__ . '/../../../config/set/type-declaration.php';

    public const string TYPE_DECLARATION_DOCBLOCKS = __DIR__ . '/../../../config/set/type-declaration-docblocks.php';

    public const string EARLY_RETURN = __DIR__ . '/../../../config/set/early-return.php';

    public const string INSTANCEOF = __DIR__ . '/../../../config/set/instanceof.php';

    public const string CARBON = __DIR__ . '/../../../config/set/datetime-to-carbon.php';

    public const string BEHAT_ANNOTATIONS_TO_ATTRIBUTES = __DIR__ . '/../../../config/set/behat-annotations-to-attributes.php';

    /**
     * @experimental Check generic types in runtime with assert. Generics for impatient people.
     */
    public const string ASSERT = __DIR__ . '/../../../config/set/assert.php';
}
