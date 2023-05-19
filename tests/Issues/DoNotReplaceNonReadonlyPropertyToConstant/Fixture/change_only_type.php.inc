<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\DoNotReplaceNonReadonlyPropertyToConstant\Fixture;

final class ChangeOnlyType
{
    /** @var string|null */
    private static $defaultFilesHash;

    /**
     * @param string[] $configFiles
     */
    public function run(array $configFiles): string
    {
        if (self::$defaultFilesHash === null) {
            self::$defaultFilesHash = 'test';
        }

        return self::$defaultFilesHash . 'test 2';
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\DoNotReplaceNonReadonlyPropertyToConstant\Fixture;

final class ChangeOnlyType
{
    private static ?string $defaultFilesHash = null;

    /**
     * @param string[] $configFiles
     */
    public function run(array $configFiles): string
    {
        if (self::$defaultFilesHash === null) {
            self::$defaultFilesHash = 'test';
        }

        return self::$defaultFilesHash . 'test 2';
    }
}

?>
