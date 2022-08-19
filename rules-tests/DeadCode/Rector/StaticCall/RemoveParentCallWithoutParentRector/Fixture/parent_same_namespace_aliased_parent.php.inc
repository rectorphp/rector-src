<?php

namespace Rector\Tests\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector\Fixture;

use Rector\Tests\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector\Fixture\OtherNamespace\ParentSameNamespaceAliasedParentParentClass as UpstreamParentClass;

class ParentSameNamespaceAliasedParentParentClass extends UpstreamParentClass {}

class ParentSameNamespaceAliasedParent extends ParentSameNamespaceAliasedParentParentClass {

    public function theMethod() {
        parent::theMethod();
    }

}

?>

