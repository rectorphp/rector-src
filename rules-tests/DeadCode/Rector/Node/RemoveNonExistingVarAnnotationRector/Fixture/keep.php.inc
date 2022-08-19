<?php

namespace Rector\Tests\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector\Fixture;

class Keep
{
    /**
     * @var string
     */
    public const SPLIT_LINE = '#-----\n#';

    public function get()
    {
        /** @var int $trainings */
        $trainings = $this->getAnotherData();

        /** @var object{foo: string} $foo */
        $foo = json_decode('{"foo": "bar"}', false);

        return $trainings;
    }
}
