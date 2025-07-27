<?php

namespace Rector\Tests\Transform\Rector\ArrayDimFetch\ArrayDimFetchToMethodCallRector\Fixture;

/** @var \SomeClass $object */
/** @var \SomeOtherClass $other */
unset($object['key1'], $other['key'], $object['key2']);

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\ArrayDimFetch\ArrayDimFetchToMethodCallRector\Fixture;

/** @var \SomeClass $object */
/** @var \SomeOtherClass $other */
unset($other['key']);
$object->unset('key1');
$object->unset('key2');

?>
