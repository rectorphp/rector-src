<?php

namespace Rector\Tests\DowngradePhp72\Rector\ClassMethod\DowngradeParameterTypeWideningRector\Fixture;

class FileLoader
{
    public function import($resource, string $type = null, bool $ignoreErrors = false, string $sourceResource = null, $exclude = null)
    {
    }
}

class ImportFileLoader extends FileLoader
{
    public function import($resource, string $type = null, $ignoreErrors = false, string $sourceResource = null, $exclude = null)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp72\Rector\ClassMethod\DowngradeParameterTypeWideningRector\Fixture;

class FileLoader
{
    /**
     * @param bool $ignoreErrors
     */
    public function import($resource, string $type = null, $ignoreErrors = false, string $sourceResource = null, $exclude = null)
    {
    }
}

class ImportFileLoader extends FileLoader
{
    public function import($resource, string $type = null, $ignoreErrors = false, string $sourceResource = null, $exclude = null)
    {
    }
}

?>
