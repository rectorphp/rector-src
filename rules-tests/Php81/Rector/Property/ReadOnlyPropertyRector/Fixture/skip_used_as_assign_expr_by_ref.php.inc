<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

/**
 * @see https://3v4l.org/e6q3d used by ref readonly is got fatal error
 */
class SkipUsedAsAssignByRef
{
    public function __construct(private string $foo)
    {}

    public function run()
    {
        $data['data'] = &$this->foo;
    }
}
