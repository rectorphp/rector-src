<?php

namespace Rector\Tests\DowngradePhp81\Rector\FuncCall\DowngradeFirstClassCallableSyntaxRector\Fixture;

use Exception;
use Symfony\Component\Config\Util\XmlUtils;

class SomeClass
{
    public function run(string $file)
    {
        try {
            $dom = XmlUtils::loadFile($file, $this->validateSchema(...));
        } catch (Exception) {}
    }

    private function validateSchema(...$args) {}
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp81\Rector\FuncCall\DowngradeFirstClassCallableSyntaxRector\Fixture;

use Exception;
use Symfony\Component\Config\Util\XmlUtils;

class SomeClass
{
    public function run(string $file)
    {
        try {
            $dom = XmlUtils::loadFile($file, \Closure::fromCallable([$this, 'validateSchema']));
        } catch (Exception) {}
    }

    private function validateSchema(...$args) {}
}

?>
