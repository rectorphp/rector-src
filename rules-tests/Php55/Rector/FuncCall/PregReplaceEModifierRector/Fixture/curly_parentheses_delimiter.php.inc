<?php

namespace Rector\Tests\Php55\Rector\FuncCall\PregReplaceEModifierRector\Fixture;

function run()
{
    $eval_links = '$link = clean_link("$1","$2")';

    echo preg_replace('{\[link:([^\|].*?)\|([^\]].*?)\]}ei',"$eval_links",$str);
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\FuncCall\PregReplaceEModifierRector\Fixture;

function run()
{
    $eval_links = '$link = clean_link("$1","$2")';

    echo preg_replace('{\[link:([^\|].*?)\|([^\]].*?)\]}i',"$eval_links",$str);
}

?>