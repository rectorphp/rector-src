<?php

declare(strict_types=1);

namespace Rector\Core\Util;

use Rector\Core\Exception\ShouldNotHappenException;

final class FileHasher {
    /**
     * @param string[] $files
     */
    public function hashFiles(array $files): string
    {
        $configHash = '';
        foreach ($files as $file) {
            $hash = hash_file('crc32b', $file);
            if ($hash === false) {
                throw new ShouldNotHappenException(sprintf('File %s is not readable', $file));
            }
            $configHash .= $hash;
        }
        return $configHash;
    }
}
