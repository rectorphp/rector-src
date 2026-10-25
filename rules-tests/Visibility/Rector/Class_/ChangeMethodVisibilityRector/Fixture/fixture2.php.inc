<?php

namespace Rector\Tests\Visibility\Rector\Class_\ChangeMethodVisibilityRector\Fixture;

use Rector\Tests\Visibility\Rector\Class_\ChangeMethodVisibilityRector\Source\ParentObject;

class ClassThatInherits2 extends ParentObject
{
    static function toBePublicStaticMethod() {

    }
}

?>
-----
<?php

namespace Rector\Tests\Visibility\Rector\Class_\ChangeMethodVisibilityRector\Fixture;

use Rector\Tests\Visibility\Rector\Class_\ChangeMethodVisibilityRector\Source\ParentObject;

class ClassThatInherits2 extends ParentObject
{
    public static function toBePublicStaticMethod() {

    }
}

?>
