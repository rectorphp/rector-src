<?php

namespace Rector\Tests\Renaming\Rector\StaticCall\RenameStaticMethodRector\Fixture;

function staticMethodNameReplacer()
{
    \Nette\Utils\Html::add('someContent');
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\StaticCall\RenameStaticMethodRector\Fixture;

function staticMethodNameReplacer()
{
    \Nette\Utils\Html::addHtml('someContent');
}

?>
