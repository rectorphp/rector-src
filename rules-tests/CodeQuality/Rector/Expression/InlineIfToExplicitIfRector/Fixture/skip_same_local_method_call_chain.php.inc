<?php

namespace Rector\Tests\CodeQuality\Rector\Expression\InlineIfToExplicitIfRector\Fixture;

class SkipSameLocalMethodCallChain
{
    public function run($asset)
    {
        $this->validateExtensionAndMimeType($asset->getExtension(), $asset->loadFile())
            && $this->validateExtensionAndMimeType($this->parseExtension($asset->getTempName()), $asset->loadFile(true))
            && $this->validateExtensionAndMimeType($this->parseExtension($asset->getOriginalFileName()), null);
    }

    private function validateExtensionAndMimeType($extension, $mimeType): bool
    {
        return $extension !== null && $mimeType !== null;
    }

    private function parseExtension($name)
    {
        return $name;
    }
}
