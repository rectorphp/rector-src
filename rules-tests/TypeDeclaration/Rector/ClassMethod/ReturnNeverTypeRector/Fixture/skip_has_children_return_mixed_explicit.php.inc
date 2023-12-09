<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\Fixture;

// both classes must be in same fixture as changed part is the parent
class SkipHasChildrenReturnMixedExplit {
    public function test() {
     	throw new \Exception("Not implemented");
    }
}

class SkipHasChildrenReturnMixedExplitChild extends SkipHasChildrenReturnMixedExplit {
 	public function test(): mixed {
     	return "foo";
    }
}
