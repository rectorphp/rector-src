<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\Fixture;

// both classes must be in same fixture as changed part is the parent
class HasChildrenReturnNever {
    public function test() {
     	throw new \Exception("Not implemented");
    }
}

class HasChildrenReturnNeverChild extends HasChildrenReturnNever {
 	public function test(): never {
        throw new \Exception("Not implemented in child as well");
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\Fixture;

// both classes must be in same fixture as changed part is the parent
class HasChildrenReturnNever {
    public function test(): never {
     	throw new \Exception("Not implemented");
    }
}

class HasChildrenReturnNeverChild extends HasChildrenReturnNever {
 	public function test(): never {
        throw new \Exception("Not implemented in child as well");
    }
}

?>
