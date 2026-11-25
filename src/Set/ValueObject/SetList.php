<?php

declare(strict_types=1);

namespace Rector\Set\ValueObject;

final class SetList
{
    public const string CODE_QUALITY = __DIR__ . '/../../../config/set/code-quality.php';

    public const string CODING_STYLE = __DIR__ . '/../../../config/set/coding-style.php';

    public const string DEAD_CODE = __DIR__ . '/../../../config/set/dead-code.php';

    public const string NAMED_ARGS = __DIR__ . '/../../../config/set/named-args.php';

    /**
     * Opinionated rules that match rector coding standard
     */
    public const string RECTOR_PRESET = __DIR__ . '/../../../config/set/rector-preset.php';

    public const string PRIVATIZATION = __DIR__ . '/../../../config/set/privatization.php';

    public const string TYPE_DECLARATION = __DIR__ . '/../../../config/set/type-declaration.php';

    public const string TYPE_DECLARATION_DOCBLOCKS = __DIR__ . '/../../../config/set/type-declaration-docblocks.php';

    public const string CARBON = __DIR__ . '/../../../config/set/datetime-to-carbon.php';

    public const string BEHAT_ANNOTATIONS_TO_ATTRIBUTES = __DIR__ . '/../../../config/set/behat-annotations-to-attributes.php';

    /**
     * All PHP version rules in one set; each rule gates itself by PHP version at runtime
     */
    public const string PHP_VERSION_BASED_SET = __DIR__ . '/../../../config/set/php-version-based.php';
}
