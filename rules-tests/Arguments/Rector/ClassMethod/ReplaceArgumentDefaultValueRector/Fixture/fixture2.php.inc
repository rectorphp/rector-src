<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Fixture;

use Symfony\Component\Yaml\Yaml;

class Fixture2 {
    function argumentDefaultValue2()
    {
        Yaml::parse('...', false, false, true);
    }
}

?>
-----
<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Fixture;

use Symfony\Component\Yaml\Yaml;

class Fixture2 {
    function argumentDefaultValue2()
    {
        Yaml::parse('...', \Symfony\Component\Yaml\Yaml::PARSE_OBJECT_FOR_MAP);
    }
}

?>
