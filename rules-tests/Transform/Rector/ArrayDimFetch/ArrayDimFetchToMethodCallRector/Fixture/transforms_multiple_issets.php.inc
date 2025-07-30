<?php

namespace Rector\Tests\Transform\Rector\ArrayDimFetch\ArrayDimFetchToMethodCallRector\Fixture;

/** @var \SomeClass $object */
/** @var \SomeOtherClass $other */
if (isset($object['key1'], $other['key'], $object['key2'])) {
    echo 'Keys are set';
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\ArrayDimFetch\ArrayDimFetchToMethodCallRector\Fixture;

/** @var \SomeClass $object */
/** @var \SomeOtherClass $other */
if (isset($other['key']) && $object->has('key1') && $object->has('key2')) {
    echo 'Keys are set';
}

?>
