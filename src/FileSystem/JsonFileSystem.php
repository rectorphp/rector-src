<?php

declare(strict_types=1);

namespace Rector\FileSystem;

use Nette\Utils\FileSystem;
use Nette\Utils\Json;

final class JsonFileSystem
{
    /**
     * @return array<string, mixed>
     */
    public static function readFilePath(string $filePath): array
    {
        $fileContents = FileSystem::read($filePath);

        return Json::decode($fileContents, forceArrays: true);
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function writeFile(string $filePath, array $data): void
    {
        $json = Json::encode($data, pretty: true);
        FileSystem::write($filePath, $json, null);
    }
}
