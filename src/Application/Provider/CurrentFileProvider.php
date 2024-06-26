<?php

declare(strict_types=1);

namespace Rector\Application\Provider;

use Rector\ValueObject\Application\File;

final class CurrentFileProvider
{
    private ?File $file = null;

    public function setFile(File $file): void
    {
        $this->file = $file;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }
}
