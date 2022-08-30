<?php
declare(strict_types=1);

namespace Rector\Testing\Fixture;

use Nette\Utils\FileSystem;
use Symplify\SmartFileSystem\SmartFileInfo;

final class FixtureTempFileDumper
{
    public static function dump(string $fileContents): SmartFileInfo
    {
        $temporaryFileName = sys_get_temp_dir() . '/rector/tests/fixture_' . md5($fileContents);
        FileSystem::write($temporaryFileName, $fileContents);

        return new SmartFileInfo($temporaryFileName);
    }
}
