<?php

namespace Rector\Tests\Issues\EmptyBooleanCompare\Fixture;

final class OnArrayObject
{
    public function checkUrl()
    {
        $parts = new \ArrayObject(['host' => 'test'], \ArrayObject::ARRAY_AS_PROPS);

        if (!empty($parts['host'])) {
            return $parts['host'];
        }

        return null;
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\EmptyBooleanCompare\Fixture;

final class OnArrayObject
{
    public function checkUrl()
    {
        $parts = new \ArrayObject(['host' => 'test'], \ArrayObject::ARRAY_AS_PROPS);

        if (isset($parts['host']) && ($parts['host'] !== '' && $parts['host'] !== '0')) {
            return $parts['host'];
        }

        return null;
    }
}

?>
