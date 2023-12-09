<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\Fixture;

// both classes must be in same fixture as changed part is the parent
class SkipHasChildrenReturnMixed {
    public function test() {
     	throw new \Exception("Not implemented");
    }
}

class SkipHasChildrenReturnMixedChild extends SkipHasChildrenReturnMixed {
 	public function test() {
     	return "foo";
    }
}
