<?php

declare(strict_types=1);

namespace Rector\Set\ValueObject;

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
     * @deprecated Niche set for a rarely used extension, it is empty now and will be removed.
     */
    public const string NAMING = __DIR__ . '/../../../config/set/naming.php';

    public const string NAMED_ARGS = __DIR__ . '/../../../config/set/named-args.php';

    /**
     * Opinionated rules that match rector coding standard
     */
    public const string RECTOR_PRESET = __DIR__ . '/../../../config/set/rector-preset.php';

    public const string PRIVATIZATION = __DIR__ . '/../../../config/set/privatization.php';

    public const string TYPE_DECLARATION = __DIR__ . '/../../../config/set/type-declaration.php';

    public const string TYPE_DECLARATION_DOCBLOCKS = __DIR__ . '/../../../config/set/type-declaration-docblocks.php';

    /**
     * @deprecated Use code-quality set instead, as all early return rules were moved there
     */
    public const string EARLY_RETURN = __DIR__ . '/../../../config/set/early-return.php';

    /**
     * @deprecated Use code-quality set instead, as most instanceof rules were moved there
     */
    public const string INSTANCEOF = __DIR__ . '/../../../config/set/instanceof.php';

    /**
     * @deprecated Use code-quality and coding-style sets instead, as the if rules were moved there or deprecated
     */
    public const string IF = __DIR__ . '/../../../config/set/if.php';

    public const string CARBON = __DIR__ . '/../../../config/set/datetime-to-carbon.php';

    public const string BEHAT_ANNOTATIONS_TO_ATTRIBUTES = __DIR__ . '/../../../config/set/behat-annotations-to-attributes.php';

    /**
     * All PHP version rules in one set; each rule gates itself by PHP version at runtime
     */
    public const string PHP_VERSION_BASED_SET = __DIR__ . '/../../../config/set/php-version-based.php';
}
