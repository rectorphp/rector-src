<?php

namespace Rector\Core\Tests\Issues\IssueTypedPropertyBelowPhp74\Fixture;

final class SomeClass
{
    public $name;

    public function getName(): string
    {
        return $this->name;
    }
}

?>
