<?php

namespace Rector\Tests\DowngradePhp80\Rector\MethodCall\DowngradeNamedArgumentRector\Fixture;

class OnFuncCall
{
    public function run()
    {
        json_decode('{}', associative: true, flags: JSON_THROW_ON_ERROR);
        json_decode('{}', flags: JSON_THROW_ON_ERROR);
        json_decode(associative: true, flags: JSON_THROW_ON_ERROR, json: '{}');
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp80\Rector\MethodCall\DowngradeNamedArgumentRector\Fixture;

class OnFuncCall
{
    public function run()
    {
        json_decode('{}', true, 512, JSON_THROW_ON_ERROR);
        json_decode('{}', null, 512, JSON_THROW_ON_ERROR);
        json_decode('{}', true, 512, JSON_THROW_ON_ERROR);
    }
}

?>
