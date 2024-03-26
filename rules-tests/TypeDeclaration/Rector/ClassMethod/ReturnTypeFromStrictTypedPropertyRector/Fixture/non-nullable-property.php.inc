<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedPropertyRector\Fixture;

final class DemoFile
{
    private ?DemoFile $demo = null;
    
    public function getDemo() {
        if ($this->demo === null) {
            $this->demo = new DemoFile();
        }
        return $this->demo;
    }
}
?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedPropertyRector\Fixture;

final class DemoFile
{
    private ?DemoFile $demo = null;
    
    public function getDemo(): DemoFile {
        if ($this->demo === null) {
            $this->demo = new DemoFile();
        }
        return $this->demo;
    }
}
?>
