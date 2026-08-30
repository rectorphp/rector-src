<?php

namespace Rector\Tests\CodeQuality\Rector\Expression\InlineIfToExplicitIfRector\Fixture;

class SkipSameLocalMethodCall
{
    public function run($asset)
    {
        $this->validateMimeType($asset->getRemoteMimeTypeFromHeader())
            && $this->validateMimeType($asset->getRemoteMimeTypeFromMagicBytes());
    }

    private function validateMimeType($mimeType): bool
    {
        return $mimeType !== null;
    }
}
