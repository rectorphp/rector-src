<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\Fixture;

// both classes must be in same fixture as changed part is the parent
class Testr4 {
    public function test() {
     	throw new \Exception("Not implemented");
    }
}

class ChildTest4 extends Testr4 {
 	public function test() {
     	return "foo";
    }
}
