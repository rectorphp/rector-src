<?php

namespace Rector\Tests\Transform\Rector\ArrayDimFetch\ArrayDimFetchToMethodCallRector\Fixture;

/** @var \SomeClass $object */
$object['key']->get()

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\ArrayDimFetch\ArrayDimFetchToMethodCallRector\Fixture;

/** @var \SomeClass $object */
$object->make('key')->get()

?>
