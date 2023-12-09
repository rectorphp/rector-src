<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\Fixture;

// both classes must be in same fixture as changed part is the parent
class Testr3 {
    public function test() {
     	throw new \Exception("Not implemented");
    }
}

class ChildTest3 extends Testr3 {
 	public function test(): never {
     	return "foo";
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\Fixture;

// both classes must be in same fixture as changed part is the parent
class Testr3 {
    public function test(): never {
     	throw new \Exception("Not implemented");
    }
}

class ChildTest3 extends Testr3 {
 	public function test(): never {
     	return "foo";
    }
}

?>