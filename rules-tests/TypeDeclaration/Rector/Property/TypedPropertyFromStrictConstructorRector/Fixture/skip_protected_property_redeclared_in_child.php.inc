<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Source\SomeFrame;

class ParentFrameReflower
{
    protected $frame;

    public function __construct(SomeFrame $frame)
    {
        $this->frame = $frame;
    }
}

class ChildFrameReflower extends ParentFrameReflower
{
    protected $frame;

    public function __construct(SomeFrame $frame)
    {
        parent::__construct($frame);
    }
}
