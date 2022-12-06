<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class PropertyWithDefaultNull
{
    private $cacheFile = null;
    private $cacheFiles = null;

    /**
     * @return string
     */
    public function coreCache($file = '')
    {
        return $file;
    }

    /**
     * Register rex_autoload in spl autoloader.
     */
    public function register()
    {
        $this->cacheFile = $this->coreCache();
        $this->cacheFiles[] = $this->coreCache();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class PropertyWithDefaultNull
{
    private ?string $cacheFile = null;
    private ?array $cacheFiles = null;

    /**
     * @return string
     */
    public function coreCache($file = '')
    {
        return $file;
    }

    /**
     * Register rex_autoload in spl autoloader.
     */
    public function register()
    {
        $this->cacheFile = $this->coreCache();
        $this->cacheFiles[] = $this->coreCache();
    }
}

?>
