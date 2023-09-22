<?php

namespace Rector\Tests\Renaming\Rector\StaticCall\RenameStaticMethodRector\Fixture;

use Rector\Tests\Renaming\Rector\StaticCall\RenameStaticMethodRector\Source\FormMacros;

function staticMethodNameReplacer2()
{
    $result = FormMacros::renderFormBegin($someArgs);
    $result = FormMACROS::renderFormBEGIN($someArgs);
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\StaticCall\RenameStaticMethodRector\Fixture;

use Rector\Tests\Renaming\Rector\StaticCall\RenameStaticMethodRector\Source\FormMacros;

function staticMethodNameReplacer2()
{
    $result = \Nette\Bridges\FormsLatte\Runtime::renderFormBegin($someArgs);
    $result = \Nette\Bridges\FormsLatte\Runtime::renderFormBegin($someArgs);
}

?>
