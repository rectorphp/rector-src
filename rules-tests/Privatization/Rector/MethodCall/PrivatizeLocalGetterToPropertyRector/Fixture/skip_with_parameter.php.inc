<?php

namespace Rector\Tests\Privatization\Rector\MethodCall\PrivatizeLocalGetterToPropertyRector\Fixture;

class SkipWithParameter
{
    public $a = 'value';

    public function run()
    {
        $var = 'a';
        return $this->getSome($var);
    }

    private function getSome(string $parameter)
    {
        return (new SkipWithParameter())->$parameter;
    }
}